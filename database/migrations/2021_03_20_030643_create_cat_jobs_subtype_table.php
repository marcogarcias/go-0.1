<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatJobsSubtypeTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create("cat_jobs_subtype", function (Blueprint $table) {
      $table->engine = "InnoDB";
      $table->bigIncrements("idJobSubType");
      $table->string("name");
      $table->string("description")->nullable();
      $table->unsignedSmallInteger("order")->nullable()->default(0);
      $table->unsignedBigInteger("jobType_id");
      $table->boolean("deleted")->nullable()->default(false);
      $table->timestamps();

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
  public function down(){
    Schema::dropIfExists("cat_jobs_subtype");
  }
}
