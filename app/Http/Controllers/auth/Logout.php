<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group Auth
 *
 * APIs for auth
 */
class Logout extends Controller
{
  /**
   * Logout
   * 
   * This endpoint enable the admin or the user to logout
   * 
   * @response 200 {
   *  "message": "You have logged out successfully"
   * }
   * 
   */
  public function logout()
  {
    auth()->logout();
    return response()->json(["message" => "You have logged out successfully"], 200);
  }
}
