<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsOnlyTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('ads_only', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idAdOnly');
      $table->string('name')->index();
      $table->string('description')->nullable();
      $table->string('path')->nullable();
      $table->string('image')->nullable();
      $table->string('url')->nullable();
      $table->unsignedBigInteger('stablishment_id');
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
    Schema::dropIfExists('ads_only');
  }
}
