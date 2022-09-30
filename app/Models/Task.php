<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
  use HasFactory;

  protected $table = 'tasks';

  protected $fillable = [
    "title",
    "description",
    "detail",
    "done",
    "project_id",
    "user_id"
  ];

  public function project()
  {
    return $this->belongsTo('App\Models\Project', 'project_id', 'id');
  }

  public function user()
  {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
  }
}
