<?php

namespace App\Helpers;

use Facebook\Facebook;
use Illuminate\Support\Facades\Log;

class FacebookLoginProvider
{
    public static function verifyToken($request)
    {
        $fb = new Facebook([
            'app_id' => env('FACEBOOK_CLIENT_ID'),
            'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'default_graph_version' => 'v2.10',
        ]);

        $accessToken = $request->get('access_token');

        try {
            $response = $fb->get('/me?fields=email,birthday', $accessToken);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            Log::error($e->getMessage());
            throw $e;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return $response->getGraphUser();
    }
}
