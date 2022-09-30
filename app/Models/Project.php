<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  use HasFactory;

  protected $table = 'projects';

  protected $fillable = [
    'title',
    'description',
  ];

  public function tasks()
  {
    return $this->hasMany('App\Models\Task', "project_id", "id");
  }
}
