<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\ExpectationFailedException;

use App\Models\User;

class LoginTest extends TestCase
{
    private $user;
    private $random;
    private $registerPath;


    public function setUp(): void
    {

        parent::setUp();
        $this->random = rand(11, 11);
        $this->user = user::find($this->random);
        $this->registerPath = 'api/auth/login';
    }

    public function __construct()
    {
        parent::__construct();
    }

    private function assertExactlyJson($payload, $expectedJson, $expectedStatus)
    {
        $response = $this->json("POST", $this->registerPath, $payload);
        $response
            ->assertStatus($expectedStatus)
            ->assertJson($expectedJson);
    }

    private function assertPartiallyJson($payload, $expectedJson, $expectedStatus)
    {
        $response = $this->json("POST", $this->registerPath, $payload);
        $response
            ->assertStatus($expectedStatus)
            ->assertJson($expectedJson);
    }

    private function assertJsonKeys($payload, $keys, $expectedStatus)
    {
        $response = $this->json("POST", $this->registerPath, $payload);
        $response
            ->assertStatus($expectedStatus)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->hasAll($keys)
            );
    }

    /**
     * @group sofyan
     */
    // sign up without email 
    public function test_login_without_email()
    {
        $payload = [
            "name" => "testName",
        ];
        $expectedJson = array(
            "email" => [
                "The email field is required."
            ]
        );
        $this->assertExactlyJson($payload, $expectedJson, 400);
    }

    public function test_login_with_wrong_credential()
    {
        $payload = [
            "email" => "sofyan@gmail.com",
            "password" => "p0ssword",
        ];
        $expectedJson = [
            "errors" => "email or password is invalid, try again"
        ];
        $this->assertExactlyJson($payload, $expectedJson, 402);
    }

    public function test_login_successfully()
    {
        $payload = [
            "email" => "sofyan@gmail.com",
            "password" => "password",
        ];
        $keys = ['token'];

        $this->assertJsonKeys($payload, $keys, 200);
    }
}
