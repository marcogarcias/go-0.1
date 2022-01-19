<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatAlertsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('chat_alerts', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idchat');
      $table->unsignedTinyInteger('type');
      $table->string('message');
      $table->unsignedBigInteger('chat_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('chat_id')
        ->references('idchat')
        ->on('chat_messages');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('chat_alerts');
  }
}
