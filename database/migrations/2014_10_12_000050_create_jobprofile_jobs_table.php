<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobprofileJobsTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('jobprofile_jobs', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idProfileJob');
      $table->unsignedBigInteger('jobProfile_id');
      $table->unsignedBigInteger('job_id');
      $table->boolean('disabled')->nullable()->default(false);
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('jobProfile_id')
        ->references('idJobProfile')
        ->on('user_jobprofile');

      $table->foreign('job_id')
        ->references('idjob')
        ->on('jobs');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(){
    Schema::dropIfExists('jobprofile_jobs');
  }
}
