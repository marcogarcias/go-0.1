<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStablishmentsJobsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("stablishments_jobs", function (Blueprint $table) {
      $table->engine = "InnoDB";
      $table->bigIncrements("idjob");
      $table->string("name")->index();
      $table->text("description");
      $table->text("requirements")->nullable();
      $table->string("documentation");
      $table->unsignedBigInteger("stablishment_id");
      $table->unsignedBigInteger("jobType_id");
      $table->boolean("deleted")->nullable()->default(false);
      $table->timestamps();

      $table->foreign("stablishment_id")
        ->references("idstablishment")
        ->on("stablishments");

      $table->foreign("jobType_id")
        ->references("idJobType")
        ->on("cat_jobs_type");
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('stablishments_jobs');
  }
}
