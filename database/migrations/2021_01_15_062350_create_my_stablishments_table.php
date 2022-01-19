<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyStablishmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('my_stablishments', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idmystablishment');
      $table->boolean('favorite')->nullable()->default(false);
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('stablishment_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('user_id')
        ->references('id')
        ->on('users');

      $table->foreign('stablishment_id')
        ->references('idstablishment')
        ->on('stablishments');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('my_stablishments');
  }
}
