<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sections', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idsection');
      $table->string('name')->index();
      $table->string('description');
      $table->string('image');
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
    Schema::dropIfExists('sections');
  }
}
