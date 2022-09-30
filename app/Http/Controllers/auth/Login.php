<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

/**
 * @group Auth
 *
 * APIs for auth
 */
class Login extends Controller
{
  /**
   * Login
   * 
   * This endpoint enable the admin or the user to login
   * 
   * @bodyParam email string required
   * @bodyParam password string required
   * 
   * @response 200 {
   *  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE2NjQ1NzY2NjQsImV4cCI6MTY2NDU4MDI2NCwibmJmIjoxNjY0NTc2NjY0LCJqdGkiOiJNbGdiTDZ4YUM2cUNxbVFLIiwic3ViIjoiMTEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.m-PDyenIrHNvv0CTIybQ3XmYHeYTWG5EJ43GylbECMA"
   * }
   * 
   * @response 400 {
   *  "email": [
   *    "The email field is required."
   *  ],
   *  "password": [
   *    "The password field is required."
   *  ]
   * }
   * 
   * @response 402 {
   *  "errors": "email or password is invalid, try again"
   * }
   */
  public function login(Request $request)
  {
    $validator = $this->getValidator($request);
    if ($validator->fails()) {
      return $this->returnResponse($validator->errors(), 400);
    } else {
      $validated = $validator->validated();
      $token = Auth::attempt($validated);
      if ($token) return $this->returnResponse(['token' => $token], 200);
      return $this->returnResponse(["errors" => "email or password is invalid, try again"], 402);
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
      'email' => ["required", "email", "exists:users"],
      'password' => ["required"],
    ];
  }
}
