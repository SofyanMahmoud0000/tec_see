<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tasks', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description')->nullable();
      $table->text('detail')->nullable();
      $table->boolean('done')->default(false);

      $table->foreignId('project_id')
        ->constrained('projects')
        ->onUpdate('cascade')
        ->onDelete('cascade');

      $table->foreignId('user_id')
        ->nullable()
        ->constrained('users')
        ->onUpdate('cascade')
        ->onDelete('cascade');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('tasks');
  }
};
