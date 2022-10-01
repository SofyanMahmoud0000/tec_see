<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = [
      'name' => "Sofyan",
      'email' => "sofyan@gmail.com",
      'password' => "password",
    ];

    $admin = [
      'name' => "Admin",
      'email' => "admin@admin.com",
      'password' => "password",
      'is_admin' => true
    ];

    User::create($user);
    User::create($admin);
  }
}
