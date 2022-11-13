<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStablishmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('stablishments', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idstablishment');
      $table->string('name')->index();
      $table->string('description');
      $table->string('description2')->nullable();
      $table->string('direction');
      $table->decimal('lat', 20, 17)->default(0.0)->index();
      $table->decimal('lng', 20, 17)->default(0.0)->index();
      $table->string('image')->nullable();
      $table->string('summary')->nullable();
      $table->string('phone')->nullable();
      $table->string('whatsapp')->nullable();
      $table->string('facebook')->nullable();
      $table->string('instagram')->nullable();
      $table->string('twitter')->nullable();
      $table->string('youtube')->nullable();
      $table->string('web')->nullable();
      $table->string('hour');
      $table->integer('likes')->nullable()->default(0);
      $table->integer('range')->nullable()->default(0);
      $table->boolean('offer')->nullable()->default(false);
      $table->boolean('disabled')->nullable()->default(false);
      $table->boolean('disabledGlobal')->nullable()->default(false);
      $table->date('expiration')->nullable();
      $table->boolean('enablechat')->nullable()->default(true);
      $table->integer('user_id')->nullable()->default(0);
      $table->unsignedBigInteger('municipio_id');
      $table->unsignedBigInteger('section_id');
      $table->unsignedBigInteger('zone_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('municipio_id')
        ->references('idmunicipio')
        ->on('municipios');

      $table->foreign('section_id')
        ->references('idsection')
        ->on('sections');

      $table->foreign('zone_id')
        ->references('idzone')
        ->on('zones');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('stablishments');
  }
}
