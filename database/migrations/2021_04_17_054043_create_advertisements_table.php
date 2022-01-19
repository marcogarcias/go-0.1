<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('advertisements', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idadvertisements');
      $table->string('name')->index();
      $table->text('description')->nullable();
      $table->string('data')->nullable();
      $table->string('notes')->nullable();
      $table->string('image')->nullable()->default('default.png');
      $table->boolean('deleted')->nullable()->default(false);
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
    Schema::dropIfExists('advertisements');
  }
}
