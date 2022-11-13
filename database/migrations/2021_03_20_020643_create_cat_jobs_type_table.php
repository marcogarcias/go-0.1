<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatJobsTypeTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('cat_jobs_type', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idJobType');
      $table->string('name');
      $table->string('description')->nullable();
      $table->unsignedSmallInteger('order')->nullable()->default(0);
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
    Schema::dropIfExists('cat_jobs_type');
  }
}
