<?php

namespace Tests;

use App\Client;
use App\Coupon;
use App\Receipt;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null)
    {
        $user = $user ?: create(User::class);
        $this->actingAs($user);

        return $this;
    }

    protected function signInApi($user = null)
    {
        $user = $user ?: $user = create(User::class);
        create(Client::class, ['user_id' => $user->id]);
        Passport::actingAs(
            $user,
            ['rejections']
        );

        return $this;
    }

    protected function signInApiWithPoints($user = null)
    {
        $user = $user ?: $user = create(User::class);
        $client = create(Client::class, ['user_id' => $user->id, 'points' => 2500]);
        create(Coupon::class, ['value' => 200, 'points' => 2200]);
        create(Receipt::class, ['creditable_id' => $client->id, 'creditable_type' => 'App\Client', 'document_type' => 'MAC', 'status_id' => 16, 'unreconciled_value' => 50000]);
        Passport::actingAs(
            $user,
            ['rejections']
        );

        return $this;
    }
}
