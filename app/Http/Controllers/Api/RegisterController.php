<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiCodes;
use App\Helpers\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorCollection;
use App\Http\Resources\UserResource;
use App\Mail\SendConfirmationCode;
use App\Notifications\UserRegistered;
use App\Traits\ApiTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use ApiTrait;

    public function changeEmail($userId, Request $request)
    {
        $validatedData = \Validator::make($request->all(), ['email' => ['required', 'email']]);

        if ($validatedData->fails()) {
            return $this->validationFailed($validatedData->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $user = User::find($userId);

        if (! $user) {
            ErrorCollection::resourceNotFound();

            return new ErrorCollection(new User());
        }

        $emailAlreadyExist = User::where('email', $request->email)->first();
        if ($emailAlreadyExist && $emailAlreadyExist->id !== (int) $userId) {
            ErrorCollection::validationFailed(__('errors.account_exist'), 4);
            if (! $emailAlreadyExist->isConfirmed()) {
                ErrorCollection::validationFailed(__('errors.unconfirmed_account_exist'), 6);
            }

            return new ErrorCollection($user);
        }

        $user->update($validatedData->validate());

        if (! \App::environment('testing')) {
            Mail::to($user->email)->send(new UserRegistered($user));
        }

        $user = [new UserResource($user->fresh())];

        return $this->restApiResponse($user, ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function resendConfirmationEmail(Request $request)
    {
        $validatedData = \Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validatedData->fails()) {
            return $this->validationFailed($validatedData->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $user = User::whereEmail($request->email)->first();

        if (! $user) {
            ErrorCollection::resourceNotFound();

            return new ErrorCollection(new User());
        }

        if ($user->isConfirmed()) {
            ErrorCollection::validationFailed(__('errors.account_already_confirmed'), 5);

            return new ErrorCollection(new User());
        }

        $user->notify(new UserRegistered($user));

        $user = [new UserResource($user->fresh())];

        return $this->restApiResponse($user, $message ?? __('account.confirmation_resent'), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function confirm(Request $request)
    {
        $validatedData = \Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'confirmation_code' => 'required',
        ]);

        if ($validatedData->fails()) {
            return $this->validationFailed($validatedData->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $user = User::whereEmail($request->email);

        if (! $user->first()) {
            ErrorCollection::resourceNotFound();

            return new ErrorCollection(new User());
        }

        $user = $user->where('confirmation_code', $request->confirmation_code)->first();

        if (! $user) {
            ErrorCollection::validationFailed(__('errors.invalid_confirmation_code'), 5);

            return new ErrorCollection(new User());
        }

        $user->confirmAccount();

        $user = [new UserResource($user->fresh())];

        return $this->restApiResponse($user, ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function sendConfirmationCode(Request $request)
    {
        $validatedData = \Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validatedData->fails()) {
            return $this->validationFailed($validatedData->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $user = User::whereEmail($request->email)->first();

        if (! $user) {
            ErrorCollection::resourceNotFound();

            return new ErrorCollection(new User());
        }

        if ($user->isConfirmed()) {
            ErrorCollection::validationFailed(__('errors.account_already_confirmed'), 5);

            return new ErrorCollection(new User());
        }

        $user->generateConfirmationCode();
        $client = $user->client;

        if (! \App::environment('testing')) {
            Mail::to($user->email)->queue(new SendConfirmationCode($user, $client));
        }

        $user = [new UserResource($user->fresh())];

        return $this->restApiResponse($user, $message ?? __('account.confirmation_resent'), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function signupActivate($token)
    {
        $user = User::where('confirmation_code', $token)->first();
        if (! $user) {
            ErrorCollection::validationFailed(__('errors.invalid_confirmation_link'), 5);

            return new ErrorCollection(new User());
        }
        $user->confirmAccount();

        return $this->restApiResponse([], $message ?? __('info.account_confirmed'), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }
}
