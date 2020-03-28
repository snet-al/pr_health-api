<?php

namespace App;

use App\Exceptions\SocialNetworkException;
use App\Helpers\ApiCodes;
use App\Helpers\Constants;
use App\Helpers\FacebookLoginProvider;
use App\Helpers\InovacionNexmoProvider;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreateUserWithFacebookRequest;
use App\Http\Requests\CreateUserWithGmailRequest;
use App\Notifications\ResetPassword;
use App\Notifications\UserRegistered;
use App\Traits\ApiTrait;
use App\Traits\ChangeState;
use App\Traits\ExposePermissions;
use App\Traits\HasPhotos;
use Carbon\Carbon;
use Google_Client;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles, ExposePermissions, ChangeState, HasPhotos, ApiTrait;

    const PHOTO_DIR = 'users';
    const FACEBOOK_PROVIDER = 'facebook';
    const GMAIL_PROVIDER = 'gmail';
    const LOCAL_PROVIDER = 'local';
    const DEVICE_TYPE_WEB = 'web';
    const DEVICE_TYPE_ANDROID = 'android';
    const DEVICE_TYPE_IOS = 'ios';
    const MAX_NUMBER_OF_SMS_FOR_TEMP_PASSWORD = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'password',
        'birthday',
        'expire_date',
        'gender',
        'phone',
        'provider',
        'provider_id',
        'is_client',
        'is_confirmed',
        'is_email_confirmed',
    ];

    protected $appends = [
        'FullName',
        'all_permissions',
        'can',
        'activeStatus',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get FullName attribute by concatenating first_name and last_name fields.
     *
     * @return bool
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return (bool) $this->is_confirmed;
    }

    /**
     * Get active users as list.
     *
     * @return mixed
     */
    public static function getActive()
    {
        $users = self::active()->get();

        return $users->pluck('FullName', 'id');
    }

    public function generateConfirmationCode($save = true)
    {
        $token = Str::random(60);

        $this->confirmation_code = $token;
        if ($save) {
            $this->save();
        }

        return $this;
    }

    public function confirmAccount()
    {
        $this->is_confirmed = true;
        $this->confirmation_code = null;
        $this->save();

        return $this;
    }

    public function photo()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function favoriteProduct($productId)
    {
        return $this->favorites()->where('favorited_type', 'App\Product')
            ->where('favorited_id', $productId)
            ->first();
    }

    public function updateOrCreateAddresses($addresses)
    {
        foreach ($addresses as $address) {
            $add = $address;
            $address = (object) $address;
            $currAddress = Address::find($address->id ?? 0);

            if (! $currAddress) {
                Address::create([
                    'address' => $address->address ?? '',
                    'city' => $address->city ?? '',
                    'country' => $address->country ?? '',
                    'zip_code' => $address->zip_code ?? '',
                    'is_default' => $address->is_default ?? false,
                    'is_active' => $address->is_active ?? true,
                    'user_id' => $this->id,
                ]);
            } else {
                $currAddress->update($add);
            }
        }
    }

    public function getProductFavorite()
    {
        $favoriteIds = $this->favorites()->where('favorited_type', 'App\Product')->pluck('favorited_id');

        $product = Product::whereIn('products.id', $favoriteIds)
            ->join('favorites', 'products.id', '=', 'favorites.favorited_id')
            ->where('products.is_saleable', true)
            ->where('products.is_active', true)
            ->where('favorites.user_id', auth()->user()->id)
            ->select('products.*', 'favorites.created_at')
            ->orderBy('favorites.created_at', 'DESC')
            ->groupBy('products.id', 'favorites.created_at')
            ->get();

        return $product;
    }

    public function reorderFavoriteProducts()
    {
        $favorites = Favorite::where('favorited_type', 'App\Product')
            ->where('user_id', auth()->user()->id)
            ->orderBy('order')
            ->get();

        $count = 1;
        foreach ($favorites as $favorite) {
            $favorite->order = $count;
            $favorite->save();
            $count++;
        }

        return $favorites->fresh();
    }

    public function getOrdersOrSalesByDates($startDate, $endDate, $page, $resourceType)
    {
        if ($resourceType == 'orders') {
            return $this->getOrdersByDates($startDate, $endDate);
        }

        if ($resourceType == 'sales') {
            return $this->getSalesByDate($startDate, $endDate);
        }

        if (request()->has('start_date') || request()->has('end_date')) {
            if (request()->has('start_date') && ! request()->has('end_date')) {
                $orders = $this->orders()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, Carbon::now()->toDateString()])
                    ->orderByDesc('created_at')
                    ->get();
            } elseif (! request()->has('start_date') && request()->has('start_date')) {
                $orders = $this->orders()
                    ->where(DB::raw('DATE(date)'), '<', $endDate)
                    ->orderByDesc('created_at')
                    ->get();
            } else {
                $orders = $this->orders()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, $endDate])
                    ->orderByDesc('created_at')
                    ->get();
            }

            $orderIds = $orders->pluck('id');

            if (request()->has('start_date') && ! request()->has('end_date')) {
                $sales = $this->client->sales()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, Carbon::now()->toDateString()])
                    ->orderByDesc('created_at')
                    ->get();
            } elseif (! request()->has('start_date') && request()->has('end_date')) {
                $sales = $this->client->sales()
                    ->where(DB::raw('DATE(date)'), '<', $endDate)
                    ->orderByDesc('created_at')
                    ->get();
            } else {
                $sales = $this->client->sales()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, $endDate])
                    ->whereNotIn('sales.order_id', $orderIds)
                    ->orderByDesc('created_at')
                    ->get();
            }
        } else {
            $orders = $this->orders()
                ->orderByDesc('created_at')
                ->get();

            $orderIds = $orders->pluck('id');

            $sales = $this->client->sales()
                ->whereNotIn('sales.order_id', $orderIds)
                ->orWhere('sales.order_id', '')
                ->orderByDesc('created_at')
                ->get();
        }

        $orders = $orders->merge($sales);
        $orders = $orders->forPage($page, 2);

        return $orders;
    }

    public function getRecentOrders()
    {
        return $this->orders()->latest()->take(6)->get();
    }

    public function countOrders()
    {
        return $this->orders()->count();
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    private function getOrdersByDates($startDate, $endDate)
    {
        if (request()->has('start_date') || request()->has('end_date')) {
            if (request()->has('start_date') && ! request()->has('end_date')) {
                $orders = $this->orders()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, Carbon::now()->toDateString()])
                    ->orderByDesc('created_at')
                    ->simplePaginate(Constants::PAGINATE);
            } elseif (! request()->has('start_date') && request()->has('end_date')) {
                $orders = $this->orders()
                    ->where(DB::raw('DATE(date)'), '<', $endDate)
                    ->orderByDesc('created_at')
                    ->simplePaginate(Constants::PAGINATE);
            } else {
                $orders = $this->orders()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, $endDate])
                    ->orderByDesc('created_at')
                    ->simplePaginate(Constants::PAGINATE);
            }
        } else {
            $orders = $this->orders()
                ->orderByDesc('created_at')
                ->simplePaginate(Constants::PAGINATE);
        }

        return $orders;
    }

    private function getSalesByDate($startDate, $endDate)
    {
        if (request()->has('start_date') || request()->has('end_date')) {
            if (request()->has('start_date') && ! request()->has('end_date')) {
                $sales = $this->client->sales()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, Carbon::now()->toDateString()])
                    ->orderByDesc('created_at')
                    ->simplePaginate(Constants::PAGINATE);
            } elseif (! request()->has('start_date') && request()->has('end_date')) {
                $sales = $this->client->sales()
                    ->where(DB::raw('DATE(date)'), '<', $endDate)
                    ->orderByDesc('created_at')
                    ->simplePaginate(Constants::PAGINATE);
            } else {
                $sales = $this->client->sales()
                    ->whereBetween(DB::raw('DATE(date)'), [$startDate, $endDate])
                    ->orderByDesc('created_at')
                    ->simplePaginate(Constants::PAGINATE);
            }
        } else {
            $sales = $this->client->sales()
                ->orderByDesc('created_at')
                ->simplePaginate(Constants::PAGINATE);
        }

        return $sales;
    }

    public static function findOrCreate($request)
    {
        try {
            $request = self::updateRequestPassword($request);
            if ($request->get('provider') != Constants::LOCAL_PROVIDER) {
                self::validateProviderTokenCompatibility($request);
                $user = User::where('email', $request->get('email'))->first();
            } else {
                $user = User::where('email', $request->get('email'))
                    ->where('password', bcrypt($request->get('password')))
                    ->first();
            }
            if ($user) {
                $user->password = bcrypt($request->get('password'));
                $user->save();

                return $user->fresh();
            }
        } catch (SocialNetworkException $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }

        return self::createFromProvider($request);
    }

    public static function validateProviderTokenCompatibility($request)
    {
        try {
            if ($request->get('provider') == Constants::FACEBOOK_PROVIDER) {
                $facebookUser = FacebookLoginProvider::verifyToken($request);
                if ($facebookUser['email'] != $request->get('email')) {
                    throw new SocialNetworkException('Request email and facebook token do not match');
                }
            } else {
                if ($request->get('provider') == Constants::GMAIL_PROVIDER) {
                    if (! $request->has('device_type')) {
                        $request['device_type'] = Constants::DEVICE_TYPE_WEB;
                    }
                    $deviceType = $request->get('device_type');

                    if ($deviceType == Constants::DEVICE_TYPE_ANDROID) {
                        $googleClientId = env('GMAIL_ANDROID_CLIENT_ID');
                    } elseif ($deviceType == Constants::DEVICE_TYPE_IOS) {
                        $googleClientId = env('GMAIL_IOS_CLIENT_ID');
                    } else {
                        $googleClientId = env('GMAIL_WEB_CLIENT_ID');
                    }

                    $googleClient = new Google_Client(['client_id' => $googleClientId]);

                    $payload = $googleClient->verifyIdToken($request->get('access_token'));
                    if ($payload && $payload['email'] != $request->get('email')) {
                        throw new SocialNetworkException('Request email and gmail token do not match');
                    }
                    if (! $payload) {
                        throw new SocialNetworkException('Gmail error occured');
                    }
                }
            }

            return true;
        } catch (SocialNetworkException $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    public static function createFromProvider($request)
    {
        if ($request->get('provider') == Constants::LOCAL_PROVIDER) {
            $validator = \Validator::make($request->all(), CreateUserRequest::rules());
        } elseif ($request->get('provider') == Constants::FACEBOOK_PROVIDER) {
            $validator = \Validator::make($request->all(), CreateUserWithFacebookRequest::rules());
        } elseif ($request->get('provider') == Constants::GMAIL_PROVIDER) {
            $validator = \Validator::make($request->all(), CreateUserWithGmailRequest::rules());
        }

        if ($validator->fails()) {
            throw new \Exception('Ju lutem plotesoni te gjitha fushat si ne dokumentacion per te regjistruar perdoruesi');
        }
        try {
            $request = self::updateRequestPassword($request);

            $user = User::create([
                'uuid' => \Webpatser\Uuid\Uuid::generate(4)->string,
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'birthday' => $request->get('birthday'),
                'phone' => $request->get('phone'),
                'is_client' => true,
                'is_confirmed' => $request->get('provider') != Constants::LOCAL_PROVIDER ? true : false,
                'is_active' => true,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }

        return $user;
    }

    public static function updateRequestPassword($request)
    {
        try {
            if (! $request->has('password') && $request->get('provider') != Constants::LOCAL_PROVIDER) {
                $request['password'] = $request->get('access_token');
            }
            if (strlen($request->get('password')) > 16) {
                $request['password'] = substr($request->get('password'), 0, 16);
            }

            return $request;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    public function changeEmail(Request $request)
    {
        $validatedData = \Validator::make($request->all(), ['email' => ['required', 'email']]);

        if ($validatedData->fails()) {
            return $this->validationFailed($validatedData->errors());
        }

        $user = auth()->user;

        $emailAlreadyExist = User::where('email', $request->get('email'))->first();
        if ($emailAlreadyExist && $emailAlreadyExist->id !== (int) $user->id) {
            return $this->validationFailed([_('api.users.email_exists')], ApiCodes::USER_EMAIL_EXISTS);
        }

        $user->email = $request->get('email');
        $user->is_confirmed = false;
        $user->save();
        $user = $user->fresh();

        if (! \App::environment('testing')) {
            $user->notify(new UserRegistered());
        }

        return $this->restApiResponse([new UserResource($user)]);
    }

    public function resetPasswordViaMail()
    {
        try {
            $this->generateTempPassword();
            $this->fresh();
            $this->notify(new ResetPassword($this));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    public function resetPassViaSms()
    {
        try {
            $this->generateTempPassword();
            if (! $this->tempPasswordCounterExpired()) {
                InovacionNexmoProvider::notifyViaNexmo($this->client->mobile_phone,
                    __('password_reset.new_password_info') . ' : ' . $this->temp_password
                );
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    public function generateTempPassword()
    {
        try {
            if ($this->tempPasswordExpired()) {
                $newPass = strtolower($this->first_name . rand(1000, 9999));
                $this->temp_password = $newPass;
                $this->temp_password_expires_at = Carbon::now()->addDay();
                $this->temp_password_reset_at = Carbon::now();
                $this->temp_password_counter = 1;
            } else {
                $this->temp_password_counter++;
            }
            $this->save();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    public function tempPasswordExpired()
    {
        return $this->temp_password_expires_at < Carbon::now();
    }

    public function tempPasswordCounterExpired()
    {
        return $this->temp_password_counter > self::MAX_NUMBER_OF_SMS_FOR_TEMP_PASSWORD;
    }
}
