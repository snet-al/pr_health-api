<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_global_configuration()
    {
        $this->signInApi();
        create(\App\Address::class);
        $response = $this->getJson('/api/global-configuration')
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [$this->getGlobalConfigurationJsonFields()],
                'errors',
            ]);
    }

    /** @skip */
    private function getGlobalConfigurationJsonFields()
    {
        return [
            'require_email_verification',
            'require_phone_number_verification',
            'allow_guest_checkout',
        ];
    }
}
