<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiCodes;
use App\Helpers\HttpStatusCodes;
use App\Traits\ApiTrait;
use App\User;
use Laravel\Passport\Http\Controllers\AccessTokenController as LaravelAccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends LaravelAccessTokenController
{
    use ApiTrait;

    public function issueToken(ServerRequestInterface $request)
    {
        $grantType = $request->getParsedBody()['grant_type'];
        if ($grantType === 'password') {
            $user = User::where('email', $request->getParsedBody()['username'])->first();
            if (! $user) {
                return $this->restApiGeneralErrorResponse(['inactive_user'], __('auth.account_inactive'), ApiCodes::USER_NOT_FOUND, HttpStatusCodes::UNAUTHORIZED);
            }

            if (! $user->isActive()) {
                return $this->restApiGeneralErrorResponse(['invalid_credentials'], __('auth.failed'), ApiCodes::USER_NOT_FOUND, HttpStatusCodes::UNAUTHORIZED);
            }
        }

        return parent::issueToken($request);
    }
}
