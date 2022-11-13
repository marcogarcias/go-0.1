<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStablishmentsJobsSubtypesTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('stablishments_jobs_subType', function (Blueprint $table) {
      $table->engine = "InnoDB";
      $table->bigIncrements("idStabJobSubType");
      $table->unsignedBigInteger("job_id");
      $table->unsignedBigInteger("jobSubType_id");
      $table->boolean("deleted")->nullable()->default(false);
      $table->timestamps();

      $table->foreign("job_id")
        ->references("idjob")
        ->on("stablishments_jobs");

      $table->foreign("jobSubType_id")
        ->references("idJobSubType")
        ->on("cat_jobs_subtype");
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(){
    Schema::dropIfExists('stablishments_jobs_subtypes');
  }
}
