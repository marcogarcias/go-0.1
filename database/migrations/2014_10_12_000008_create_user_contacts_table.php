<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserContactsTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('user_contacts', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idContact');
      $table->string('name')->nullable();
      $table->string('description')->nullable();
      $table->date('birthday')->nullable();
      $table->string('phone')->nullable();
      $table->string('whatsapp')->nullable();
      $table->string('facebook')->nullable();
      $table->string('instagram')->nullable();
      $table->string('telegram')->nullable();
      $table->string('snapchat')->nullable();
      $table->string('tiktok')->nullable();
      $table->string('youtube')->nullable();
      $table->unsignedBigInteger('userFrom_id');
      $table->unsignedBigInteger('userTo_id');
      $table->boolean('disabled')->nullable()->default(false);
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

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
    Schema::dropIfExists('user_contacts');
  }
}
