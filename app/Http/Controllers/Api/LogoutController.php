<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiCodes;
use App\Helpers\HttpStatusCodes;
use App\Http\Controllers\Controller;
use App\Traits\ApiTrait;

class LogoutController extends Controller
{
    use ApiTrait;

    public function delete()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return $this->restApiResponse([], ApiCodes::getSuccessMessage(), ApiCodes::SUCCESS, [], HttpStatusCodes::OK);
    }
}
