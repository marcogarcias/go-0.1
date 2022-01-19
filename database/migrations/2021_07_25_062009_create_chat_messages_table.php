<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('chat_messages', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idchat');
      $table->text('message');
      $table->boolean('viewed')->nullable()->default(false);
      $table->string('from');
      $table->unsignedBigInteger('userclient_id');
      $table->unsignedBigInteger('userstablishment_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('userclient_id')
        ->references('id')
        ->on('users');

      $table->foreign('userstablishment_id')
        ->references('id')
        ->on('users');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('chat_messages');
  }
}
