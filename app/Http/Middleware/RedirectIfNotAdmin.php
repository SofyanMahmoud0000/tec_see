<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotAdmin
{
  public function handle(Request $request, Closure $next)
  {
    if (!auth()->user()->is_admin) {
      return response()->json(["errors" => "forbidden"], 403);
    }
    return $next($request);
  }
}
