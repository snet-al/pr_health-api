<?php

namespace App\Http\Controllers\Api;

use App\Client;
use App\Helpers\ApiCodes;
use App\Helpers\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiEditUserRequest;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Notifications\ClientPhoneConfirmation;
use App\Notifications\UserUpdated;
use App\Traits\ApiTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $query = User::where('id', '>', 0);
        if (request()->has('q')) {
            $searchParam = request()->get('q');
            $query->where('first_name', 'LIKE', '%' . $searchParam . '%')
                ->orWhere('last_name', 'LIKE', '%' . $searchParam . '%')
                ->orWhere('email', 'LIKE', '%' . $searchParam . '%');
        }

        if (request()->has('is_active')) {
            $activeStates = explode(',', request()->get('is_active'));
            $query->whereIn('is_active', $activeStates);
        }

        $users = $query->paginate(request()->get('page-size'));

        return UserResource::collection($users);
    }

    public function getAuthUser()
    {
        $data = [new UserResource(auth()->user())];

        return $this->restApiResponse($data, ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function show($userId)
    {
        $user = User::find($userId);

        if (! $user) {
            return $this->resourceNotFound(ApiCodes::getUserNotFoundMessage());
        }

        return $this->restApiResponse([new UserResource($user)], ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function update($userId, Request $request)
    {
        $user = auth()->user();

        $validator = \Validator::make($request->all(), ApiEditUserRequest::rules($user->id, $user->client->id));

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(), ApiCodes::VALIDATION_FAILED);
        }

        if ($request->has('first_name')) {
            $user->first_name = $request->first_name;
        }

        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }

        $updatedEmail = false;
        if ($request->has('email') && $user->email !== trim($request->email)) {
            $user->email = trim($request->email);
            $user->is_confirmed = false;
            $user->generateConfirmationCode(false);
            $updatedEmail = true;
        }

        if ($request->has('password') && $request->has('confirm_password')) {
            if ($request->password == $request->confirm_password) {
                $user->password = bcrypt($request->password);
            }
        }

        if ($request->has('birthday')) {
            $user->birthday = $request->birthday;
        }

        if ($request->has('phone')) {
            $phone = stripSpaces($request->phone);
            $request['phone'] = $phone;
            if (strlen($phone) !== 13) {
                \Log::info('Client update failed because of invalid phone!', $request->toArray(), $user->client->toArray());

                return $this->validationFailed([__('validation.phone.length')], ApiCodes::VALIDATION_FAILED);
            }

            $currentClientsWithSimilarPhone = Client::where('phone', $phone)
                ->orWhere('mobile_phone', $phone)
                ->count();

            if ($currentClientsWithSimilarPhone > 1) {
                \Log::info('Client update failed because phone already exists!', $request->toArray(), $user->client->toArray());

                return $this->validationFailed([__('validation.phone.exists')], ApiCodes::VALIDATION_FAILED);
            }
        }

        if ($this->isValidRequestParam($request, 'photo')) {
            $user->createPhoto(request());
        }

        return \DB::transaction(function () use ($user, $request, $updatedEmail) {
            $user->save();
            $updateClient = $this->updateClient($user, $request);

            if ($updateClient === false) {
                \Log::info('Client could not be updated!', $request->toArray(), $user->client->toArray());

                \DB::rollback();

                return $this->generalError(__('client.not_updated'), 1);
            }

            if ($updatedEmail) {
                $user->notify(new UserUpdated($user));
            }

            return $this->restApiResponse([new UserResource($user->fresh())], ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
        });
    }

    public function changeStatus($userId, Request $request)
    {
        $user = User::find($userId);

        if (! $user) {
            return $this->resourceNotFound(ApiCodes::getUserNotFoundMessage());
        }

        $action = $request->get('action');

        $user->changeActiveState($action);

        return $this->restApiResponse([new UserResource($user->fresh())], $message ?? __('users.model'). __($action), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function getFavorites()
    {
        $products = auth()->user()->getProductFavorite();

        $data = ProductResource::collection($products);

        return $this->restApiResponse($data, ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function addPhoto($userId)
    {
        $user = User::find($userId);

        if (! $user) {
            return $this->resourceNotFound(ApiCodes::getUserNotFoundMessage());
        }

        if ($user->photos->count() > 0) {
            $user->deletePhotos();
        }

        $photo = $user->createPhoto(request());

        return $this->restApiResponse([new PhotoResource($photo->fresh())], ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    private function updateClient($user, $request)
    {
        if (! $client = $user->client) {
            return $user;
        }

        if ($request->has('last_name')) {
            $client->name = $user->Fullname;
        }

        if ($request->has('email')) {
            $client->email = $request->email;
        }

        if ($request->has('subscribe_to_menu')) {
            $client->subscribe_to_menu = $request->subscribe_to_menu;
        }

        if ($request->has('phone') && $client->phone !== stripSpaces($request->phone)) {
            $date = new Carbon();
            $date->subWeek();

            if ($client->phone_updated_at > $date->toDateTimeString()) {
                \Log::info('Client is trying to update phone within 1 week.', $request->toArray());

                return false;
            }

            $client->phone = stripSpaces($request->phone);
            $client->is_confirmed = 0;
            $client->phone_updated_at = Carbon::now();
            $client->generatePhoneConfirmationCode();
            $client->notify(new ClientPhoneConfirmation($client));
        }

        if ($request->has('mobile_phone')) {
            $client->mobile_phone = $request->mobile_phone;
        }

        if ($request->has('gender')) {
            $client->gender = $request->gender;
        }

        $client->save();

        return $user;
    }

    private function isValidRequestParam($request, $param)
    {
        return $request->has('photo') && $request->get('photo') && $request->get('photo') !== '';
    }

    public function changePassword(Request $request)
    {
        if (! $request->has('old_password') && $request->get('old_password') == '') {
            return $this->singleValidationFailed(__('password.old_password_is_required'));
        }

        if (! $request->has('new_password') && $request->get('new_password') == '') {
            return $this->singleValidationFailed(__('password.new_password_is_required'));
        }

        $user = auth()->user();

        if (! (Hash::check($request->get('old_password'), $user->password))) {
            return $this->singleValidationFailed(__('password.user_with_this_password_not_found'));
        }

        $user->password = bcrypt($request->get('new_password'));
        $user->save();

        return $this->successResponse();
    }

    public function resetPassword(Request $request)
    {
        try {
            if (! $request->filled('mobile_phone') && ! $request->filled('email')) {
                return $this->validationFailed([__('password_reset.email_or_mobile_required')]);
            }

            //IF REQUEST HAS MOBILE PHONE RESET VIA MOBILE PHONE

            if ($request->filled('mobile_phone')) {
                $client = Client::where('phone', $request->get('mobile_phone'))
                    ->orWhere('mobile_phone', $request->get('mobile_phone'))->first();
                if (! $client) {
                    return $this->resourceNotFound([__('password_reset.client_not_found')], null, ApiCodes::USER_NOT_FOUND);
                }

                $user = $client->user;

                if (! $user) {
                    return $this->resourceNotFound([__('password_reset.client_not_found')], null, ApiCodes::USER_NOT_FOUND);
                }

                $user->resetPassViaSms();
                if ($user->tempPasswordCounterExpired()) {
                    $errorMessage = __('password_reset.maximum_number_of_sms').' '.User::MAX_NUMBER_OF_SMS_FOR_TEMP_PASSWORD;
                    $errorMessage = $errorMessage.' '. __('password_reset.times_per_day');

                    return $this->restApiGeneralErrorResponse([$errorMessage], $errorMessage, ApiCodes::USER_EXCEEDED_MAX_NUMBER_OF_SMS_PER_DAY);
                }

                return $this->restApiResponse([], __('password_reset.new_password_sent_via_mobile_phone'));
            }

            if ($request->filled('email')) {
                $user = User::where('email', $request->get('email'))->first();

                if (! $user) {
                    return $this->resourceNotFound([__('password_reset.client_not_found')], null, ApiCodes::USER_NOT_FOUND);
                }
                $user->resetPasswordViaMail();

                return $this->restApiResponse([], __('password_reset.new_password_sent_via_mail'));
            }

            //if something unexpected occurs return a general error message
            return $this->restApiGeneralErrorResponse([__('errors.unexpected_error_occurred')]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return $this->restApiGeneralErrorResponse([$exception->getMessage()]);
        }
    }
}
