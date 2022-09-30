<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;

use App\Models\Project;
use App\Models\Task;

class User extends Authenticatable implements JWTSubject
{
  use HasApiTokens, HasFactory, Notifiable;

  protected $table = 'users';

  protected $fillable = [
    'name',
    'email',
    'password',
    'is_admin',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function setPasswordAttribute($pass)
  {
    $this->attributes['password'] = Hash::make($pass);
  }

  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  public function tasks()
  {
    return $this->hasMany('App\Models\Task', 'user_id', 'id');
  }

  public function projects()
  {

    $projects = DB::table('projects')
            ->join('tasks', 'tasks.project_id', '=', 'projects.id')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->where('users.id', '=', $this->id)
            ->select('projects.*')
            ->distinct()
            ->get();

    return $projects;
  }
}
