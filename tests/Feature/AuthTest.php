<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_register(): void
    {
        $payload = [
            'name' => 'Test Name',
            'email' => 'test@test.com',
            'password' => Hash::make('test_password'),
            'phone' => '1234567890',
            'role' => 'customer'
        ];

        $response = $this->json('POST', 'api/register', $payload);
        $response->assertStatus(200)->assertJson(function (AssertableJson $json) {
            $json->where('success', true)
                ->where('message', 'User Registered Successfully')
                ->where('data.name', 'Test Name')
                ->where('data.email', 'test@test.com')
                ->where('data.phone', '1234567890')
                ->where('data.role', 'customer')
                ->etc();
        })->assertJsonStructure([
            'success',
            'message',
            'data',
            'errors'
        ]);

    }

    public function test_login()
    {

    }
}
