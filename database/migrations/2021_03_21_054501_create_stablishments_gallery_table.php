<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStablishmentsGalleryTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stablishments_gallery', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idgallery');
      $table->string('name')->nullable();
      $table->string('description')->nullable();
      $table->string('path')->nullable();
      $table->string('image')->nullable();
      $table->unsignedSmallInteger('order')->nullable();
      $table->boolean('disabled')->nullable()->default(false);
      $table->unsignedBigInteger('stablishment_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

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
    Schema::dropIfExists('stablishments_gallery');
  }
}
