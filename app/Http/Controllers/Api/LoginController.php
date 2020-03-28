<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\ClientDevice;
use App\ClientGroupMember;
use App\Helpers\ApiCodes;
use App\Helpers\Constants;
use App\Helpers\HttpStatusCodes;
use App\Helpers\UserApiCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\CreateUserWithFacebookRequest;
use App\Http\Requests\CreateUserWithGmailRequest;
use App\Http\Resources\UserResource;
use App\Notifications\UserRegistered;
use App\Traits\ApiTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers, ApiTrait;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            if ($request->get('provider') != Constants::LOCAL_PROVIDER) {
                if (! User::validateProviderTokenCompatibility($request)) {
                    return $this->resourceNotFound('Perdoruesi nuk u gjet ne sistem');
                }
                $user = User::where('email', $request->get('email'))->first();
                if ($user) {
                    $request = User::updateRequestPassword($request);
                    $user->password = bcrypt($request->get('password'));
                    $user->save();
                    Auth::login($user->fresh());
                    $user->bearer = self::getTokenForSocialNetworkLogins($request);

                    return $this->restApiResponse([new UserResource($user)]);
                }
            }
            $validator = \Validator::make($request->all(), ['email' => 'required', 'password' => 'required']);

            if ($validator->fails()) {
                return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
            }
            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                if ($validator->fails()) {
                    $errors = $this->transformValidationMessages($validator->errors());

                    return $this->restApiResponse([], 'Error Occurred', ApiCodes::VALIDATION_FAILED, [$errors],
                            HttpStatusCodes::UNPROCESSABLE_ENTITY);
                }

                // If the class is using the ThrottlesLogins trait, we can automatically throttle
                // the login attempts for this application. We'll key this by the username and
                // the IP address of the client making these requests into this application.
                if ($this->hasTooManyLoginAttempts($request)) {
                    $this->fireLockoutEvent($request);

                    return $this->sendLockoutResponse($request);
                }
            }

            //AttemptLoginViaTempPassword first
            $user = User::where('temp_password', $request->get('password'))
                    ->where('email', $request->get('email'))->first();

            if ($user) {
                if ($user->tempPasswordExpired()) {
                    return $this->restApiGeneralErrorResponse([__('password_reset.password_expired')], __('password_reset.password_expired'), USER_PASSWORD_EXPIRED);
                }

                auth()->login($user);
                $user->tepm_password_expires_at = Carbon::now();
                $user->save();
                $user->bearer = self::getTokenForSocialNetworkLogins($request);

                return $this->restApiResponse([new UserResource($user)]);
            }

            if ($this->attemptLogin($request)) {
                if (! auth()->user()->isActive()) {
                    \Log::info('Inactive user with ID ' . auth()->user()->id . ' trying to login via API');
                    auth()->logout();

                    return $this->restApiResponse([], $message ?? __('auth.account_inactive'),
                            ApiCodes::USER_AUTHENTICATION_FAILED, [__('auth.account_inactive')],
                            HttpStatusCodes::UNAUTHORIZED);
                }
                $user = auth()->user();
                $user->bearer = self::getTokenForSocialNetworkLogins($request);

                $user->bearer = self::getTokenForSocialNetworkLogins($request);

                return $this->restApiResponse([new UserResource($user)]);
            }

            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number o attempts they will get locked out.
            $this->incrementLoginAttempts($request);

            return $this->restApiResponse([], $message ?? __('auth.failed'), ApiCodes::USER_AUTHENTICATION_FAILED,
                    [__('auth.failed')], HttpStatusCodes::UNAUTHORIZED);
        } catch (\Exception $exception) {
            return $this->restApiGeneralErrorResponse([$exception->getMessage()]);
        }
    }

    public function register(Request $request)
    {
        try {
            if ($request->get('provider') == Constants::LOCAL_PROVIDER) {
                $validator = \Validator::make($request->all(), CreateUserRequest::rules());
            } elseif ($request->get('provider') == Constants::FACEBOOK_PROVIDER) {
                $validator = \Validator::make($request->all(), CreateUserWithFacebookRequest::rules());
            } else {
                $validator = \Validator::make($request->all(), CreateUserWithGmailRequest::rules());
            }

            if ($validator->fails()) {
                $errors = $this->transformValidationMessages($validator->errors());

                return $this->restApiResponse([], 'Error Occurred', ApiCodes::VALIDATION_FAILED, $errors,
                    HttpStatusCodes::UNPROCESSABLE_ENTITY);
            }

            $alreadyRegisteredUser = User::where('email', $request->get('email'))->first();

            if ($request->get('provider') != Constants::LOCAL_PROVIDER && $alreadyRegisteredUser) {
                if ($alreadyRegisteredUser->provider == Constants::FACEBOOK_PROVIDER) {
                    return $this->restApiGeneralErrorResponse([_('api.user_registered_with').Constants::FACEBOOK_PROVIDER], _('api.user_registered_with').Constants::FACEBOOK_PROVIDER, UserApiCodes::USER_REGISTERED_WITH_FACEBOOK);
                }
                if ($alreadyRegisteredUser->provider == Constants::GMAIL_PROVIDER) {
                    return $this->restApiGeneralErrorResponse([_('api.user_registered_with').Constants::GMAIL_PROVIDER], _('api.user_registered_with').Constants::GMAIL_PROVIDER, UserApiCodes::USER_REGISTERED_WITH_GMAIL);
                }
            }

            DB::beginTransaction();

            if ($request->filled('mobile_phone')) {
                $request['phone'] = stripSpaces($request->get('mobile_phone'));

                $alreadyRegisteredPhoneNumber = Client::where('phone', $request->phone)
                   ->orWhere('mobile_phone', $request->get('mobile_phone'))->first();

                if ($alreadyRegisteredPhoneNumber) {
                    return $this->restApiResponse([], __('errors.account_exist_with_phone'), ApiCodes::RESOURCE_EXISTS, [], HttpStatusCodes::CONFLICT);
                }
            }
            $user = User::findOrCreate($request);
            $request['user_id'] = $user->id;
            $request = User::updateRequestPassword($request);

            $user->generateConfirmationCode();

            if (! isset($user->client)) {
                $client = Client::createFromProvider($request);
            } else {
                $client = $user->client;
            }
            if ($request->has('photo')) {
                $user->createPhoto(request());
            }

            if (config('group.group_id')) {
                ClientGroupMember::create([
                        'client_group_id' => config('group.group_id'),
                        'client_id' => $client->id,
                    ]);
            }

            if ($request->get('provider') == Constants::LOCAL_PROVIDER) {
                if (! \App::environment('testing')) {
                    $user->notify(new UserRegistered());
                }
            }

            DB::commit();
            $user = $user->fresh();

            $user->bearer = self::getTokenForSocialNetworkLogins($request);

            try {
                $client = $user->client;
                $client->generatePhoneConfirmationCode();
                if ($request->filled('mobile_phone')) {
                    $client->notifyPhoneConfirmationViaNexmo();
                }
            } catch (\Exception $ex) {
                return $this->restApiGeneralErrorResponse([$ex->getMessage()]);
            }

            return $this->restApiResponse([new UserResource($user)]);
        } catch (\Exception $exception) {
            DB::rollBack();

            return $this->restApiGeneralErrorResponse([$exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        request()['resource_message'] = 'Success';

        $data = [new UserResource(new User())];

        return $this->restApiResponse($data, ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request['username'] = $request->get('email');
        $this->clearLoginAttempts($request);

        $user = $this->guard()->user();
        $data = [new UserResource($user)];
        $this->registerFCMClientDeviceToken($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: $this->restApiResponse($data, ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    /**
     * @param $request
     */
    protected function registerFCMClientDeviceToken(Request $request)
    {
        if ($request->has('fcm_details')) {
            ClientDevice::store($request);
        }
    }

    /**
     * @param Request $request
     */
    protected function deleteFCMClientDeviceToken(Request $request)
    {
        if ($request->has('fcm_details')) {
            ClientDevice::deleteDevice($request);
        }
    }

    /**
     * @param array $data
     * @param null  $message
     * @param null  $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse($data = [], $message = null, $status = null)
    {
        $message = $message ?: __('auth.failed');
        $status = $status ?: ApiCodes::USER_AUTHENTICATION_FAILED;

        return $this->restApiResponse($data, $message, $status, [], HttpStatusCodes::UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param array   $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLockoutResponse(Request $request, $data = [])
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = \Lang::get('auth.throttle', ['seconds' => $seconds]);
        $status = ApiCodes::TOO_MANY_LOGIN_ATTEMPTS;

        return $this->restApiResponse($data, $message, $status, [], HttpStatusCodes::TOO_MANY_REQUESTS);
    }

    private static function createTokenRequest(Request $request)
    {
        try {
            $requestBody = [
                'grant_type' => 'password',
                'client_id' => (int) env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username' => $request->get('email'),
                'password' => $request->get('password'),
                'scope' => '*',
            ];

            return Request::create('/api/oauth/token', 'POST', $requestBody);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            throw $ex;
        }
    }

    private function getTokenForSocialNetworkLogins(Request $request)
    {
        try {
            $bearerRequest = self::createTokenRequest($request);
            $bearer = app()->handle($bearerRequest);
            $bearer = json_decode($bearer->getContent());

            return $bearer;
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            throw $ex;
        }
    }
}
