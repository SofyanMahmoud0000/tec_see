<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\ExpectationFailedException;

use App\Models\User;

class SignUpTest extends TestCase
{
    private $user;
    private $random;
    private $registerPath;


    public function setUp(): void
    {

        parent::setUp();
        $this->random = rand(11, 11);
        $this->user = user::find($this->random);
        $this->registerPath = 'api/auth/register';
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
    public function test_register_without_email()
    {
        $payload = [
            "name" => "testName",
            "password" => "password",
            "password_confirmation" => "password"
        ];
        $expectedJson = array(
            "email" => [
                "The email field is required."
            ]
        );
        $this->assertExactlyJson($payload, $expectedJson, 400);
    }

    public function test_register_with_wrong_password_confirmation()
    {
        $payload = [
            "name" => "testName",
            "email" => "test@gmail.com",
            "password" => "password",
            "password_confirmation" => "possword"
        ];
        $expectedJson = array(
            "password" => [
                "The password confirmation does not match."
            ]
        );
        $this->assertExactlyJson($payload, $expectedJson, 400);
    }

    public function test_register_with_already_existing_email()
    {
        $payload = [
            "name" => "testName",
            "email" => $this->user->email,
            "password" => "password",
            "password_confirmation" => "password"
        ];
        $expectedJson = array(
            "email" => [
                "The email has already been taken."
            ]
        );
        $this->assertExactlyJson($payload, $expectedJson, 400);
    }

    public function test_register_successfully()
    {
        $payload = [
            "name" => "testName",
            "email" => "test@gmail.com",
            "password" => "password",
            "password_confirmation" => "password"
        ];
        $keys = ['token'];

        $rowInDatabase = [
            'email' => "test@gmail.com",
            'name' => "testName",
        ];

        try{
            $this->assertJsonKeys($payload, $keys, 201);
            $this->assertDatabaseHas('users', $rowInDatabase);
            $user = User::where('email', '=', 'test@gmail.com')->first();
            $this->assertAuthenticatedAs($user);
            User::where('email', '=', 'test@gmail.com')->delete();
        } catch(ExpectationFailedException $e){
            User::where('email', '=', 'test@gmail.com')->delete();
            throw $e;
        }
    }
}
