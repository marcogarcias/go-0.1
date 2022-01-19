<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStablishmentsTagsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stablishments_tags', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('id');
      $table->unsignedBigInteger('stablishment_id');
      $table->unsignedBigInteger('tag_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('stablishment_id')
        ->references('idstablishment')
        ->on('stablishments');

      $table->foreign('tag_id')
        ->references('idtag')
        ->on('tags');
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stablishments_tags');
    }
}
