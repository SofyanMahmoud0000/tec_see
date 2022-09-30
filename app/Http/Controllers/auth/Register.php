<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

/**
 * @group Auth
 *
 * APIs for auth
 */
class Register extends Controller
{
  /**
   * Register
   * 
   * This endpoint enable the admin or the user to register
   * 
   * @bodyParam name string required
   * @bodyParam email string required
   * @bodyParam password string required
   * @bodyParam password_confirmation string required Enter the password again to confirm it
   * 
   * @response 200 {
   *  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2NjQ1NzY2NjQsImV4cCI6MTY2NDU4MDI2NCwibmJmIjoxNjY0NTc2NjY0LCJqdGkiOiJNbGdiTDZ4YUM2cUNxbVFLIiwic3ViIjoiMTEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.m-PDyenIrHNvv0CTIybQ3XmYHeYTWG5EJ43GylbECMA"
   * }
   * 
   * @response 400 {
   *  "name": [
   *    "The name field is required."
   *  ],
   *  "email": [
   *    "The email has already been taken."
   *  ],
   *  "password": [
   *    "The password confirmation does not match."
   *  ]
   * }
   */
  public function register(Request $request)
  {
    $validator = $this->getValidator($request);
    if ($validator->fails()) {
      return $this->returnResponse($validator->errors(), 400);
    } else {
      $validated = $validator->validated();
      User::create($validated);
      $token = Auth::attempt(["email" => $request->email, "password" => $request->password]);
      if ($token) return $this->returnResponse(['token' => $token], 201);
      return $this->returnResponse(["errors" => "Something went wrong, please try again"], 200);
    }
  }

  public function getValidator(Request $request)
  {
    $validator = Validator::make($request->all(), $this->getValidationRules());
    return $validator;
  }

  public function returnResponse($res, $code)
  {
    return response()->json($res, $code);
  }

  public function getValidationRules()
  {
    return [
      'name' => ["required", "string"],
      'email' => ["required", "email", "unique:users"],
      'password' => ["required", "confirmed"],
    ];
  }
}
