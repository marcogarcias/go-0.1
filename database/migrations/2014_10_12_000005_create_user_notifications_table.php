<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('user_notifications', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idUserNotification');
      $table->string('name');
      $table->string('description')->nullable();
      $table->string('view')->nullable();
      $table->unsignedBigInteger('notification_id');
      $table->unsignedBigInteger('userFrom_id');
      $table->unsignedBigInteger('userTo_id');
      $table->boolean('disabled')->nullable()->default(false);
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('notification_id')
        ->references('idNotification')
        ->on('cat_notifications');

      $table->foreign('userFrom_id')
        ->references('id')
        ->on('users');

      $table->foreign('userTo_id')
        ->references('id')
        ->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(){
    Schema::dropIfExists('user_notifications');
  }
}
