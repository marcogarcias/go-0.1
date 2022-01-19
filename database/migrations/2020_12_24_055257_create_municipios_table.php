<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('municipios', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idmunicipio');
      $table->string('name')->index();
      $table->unsignedBigInteger('estado_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('estado_id')
        ->references('idestado')
        ->on('estados');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('municipios');
  }
}
