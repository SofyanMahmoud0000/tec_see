<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

use App\Http\Controllers\auth\Login;
use App\Http\Controllers\auth\Register;
use App\Http\Controllers\auth\Logout;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;


Route::prefix('auth')->group(function () {
  Route::middleware('guest')->group(function () {
    Route::post("/login", [Login::class, 'login']);
    Route::post("/register", [Register::class, 'register']);
  });

  Route::middleware('auth')->group(function () {
    Route::delete('/logout', [Logout::class, 'logout']);
  });
});

Route::prefix('projects')->group(function () {
  Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/users', [UserController::class, 'getProjects']);
    Route::get('/{id}/users', [UserController::class, 'getProject']);
  });

  Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/', [ProjectController::class, 'create']);
    Route::get('/', [ProjectController::class, 'list']);
    Route::get('/{id}', [ProjectController::class, 'get']);
    Route::put('/{id}', [ProjectController::class, 'update']);
    Route::delete('/{id}', [ProjectController::class, 'delete']);
  });

  
  

  
});


Route::prefix('tasks')->group(function () {

  Route::middleware(['auth', 'user'])->group(function () {
    Route::post('/{id}/submit', [TaskController::class, 'submit']);
    Route::get("/users", [UserController::class, 'getAllTasks']);
    Route::get("/submitted/users", [UserController::class, 'getSubmittedTasks']);
    Route::get("/pending/users", [UserController::class, 'getPendingTasks']);
    Route::get('/{id}/users', [UserController::class, 'getTask']);
  });

  Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/', [TaskController::class, 'create']);
    Route::get('/', [TaskController::class, 'list']);
    Route::get('/{id}', [TaskController::class, 'get']);
    Route::put('/{id}', [TaskController::class, 'update']);
    Route::delete('/{id}', [TaskController::class, 'delete']);
    Route::get('/{id}/assign_employee/{user_id}', [TaskController::class, 'assignEmployee']);
    Route::get('/{id}/no_employee', [TaskController::class, 'noEmployee']);

  });
});


// Route::get('test/{id}', function ($id) {
//   $user = User::find($id);
//   $tasks = $user->tasks;
//   return response()->json(['user' => $user]);
// });
