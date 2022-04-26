<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserJobprofileTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('user_jobprofile', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idJobProfile');
      $table->string('name');
      $table->string('nextName')->nullable();
      $table->string('ap');
      $table->string('am')->nullable();
      $table->string('fullName')->index();
      $table->string('email')->index();
      $table->string('cellphone')->nullable();
      $table->unsignedSmallInteger('age')->nullable();
      $table->boolean('gender')->default(false);
      $table->text('description')->nullable();
      $table->text('academicHistory')->nullable();
      $table->text('jobHistory')->nullable();
      $table->string('photoPath')->nullable();
      $table->string('photoName')->nullable();
      $table->boolean('disabled')->nullable()->default(false);
      $table->boolean('disabledGlobal')->nullable()->default(false);
      $table->unsignedBigInteger('user_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('user_id')
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
    Schema::dropIfExists('user_jobprofile');
  }
}
