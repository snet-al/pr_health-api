<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiCodes;
use App\Helpers\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorCollection;
use App\Notifications\PasswordChanged;
use App\Notifications\ResetPassword;
use App\Traits\ApiTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ApiTrait;

    public function forgotPassword(Request $request)
    {
        $validatedData = \Validator::make($request->all(), ['email' => ['required', 'email']]);

        if ($validatedData->fails()) {
            return $this->validationFailed($validatedData->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $user = User::whereEmail($request->email)->first();

        if (! $user) {
            ErrorCollection::resourceNotFound();

            return new ErrorCollection(new User());
        }

        if (! $user->isConfirmed()) {
            ErrorCollection::validationFailed(__('errors.account_not_confirmed'), 5);

            return new ErrorCollection(new User());
        }

        $user->expire_date = Carbon::now()->addHours(2);
        $user->save();

        $user->notify(new ResetPassword($user));

        return $this->restApiResponse([], $message ?? __('mail.forgot_password_email_send'), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }

    public function resetPassword(Request $request)
    {
        $validatedData = \Validator::make($request->all(), [
            'uuid' => 'required',
            'password' => 'required',
        ]);

        if ($validatedData->fails()) {
            return $this->validationFailed($validatedData->errors(), ApiCodes::VALIDATION_FAILED);
        }

        $user = User::where('uuid', $request->uuid)->first();
        if (! $user) {
            return $this->resourceNotFound(ApiCodes::getUserNotFoundMessage());
        }
        if ($user->expire_date <= Carbon::now()) {
            return $this->validationFailed([__('reset.link_expired')], ApiCodes::VALIDATION_FAILED);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $user->notify(new PasswordChanged($user));

        return $this->restApiResponse([], $message ?? __('mail.reset_password_done'), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }
}
