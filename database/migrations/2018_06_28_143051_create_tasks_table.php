<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('tasks', function (Blueprint $table) {
      $table->string('uuid');
      $table->uuid('task_list_uuid');
      $table->string('title');
      $table->string('comment')->nullable();
      $table->timestamp('completed_at')->nullable();
      $table->timestamps();
      $table->primary('uuid');
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
}
