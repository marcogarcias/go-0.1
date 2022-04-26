<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatNotificationsTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('cat_notifications', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idNotification');
      $table->string('name')->index();
      $table->string('description');
      $table->string('icon');
      $table->string('color');
      $table->boolean('disabled')->nullable()->default(false);
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(){
    Schema::dropIfExists('cat_notifications');
  }
}
