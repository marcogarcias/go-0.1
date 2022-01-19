<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusProductsTable extends Migration{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('menus_products', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idproduct');
      $table->string('name')->index();
      $table->text('description');
      $table->decimal('price', 12, 2)->default(0.00);
      $table->decimal('price_discount', 12, 2)->default(0.00);
      $table->boolean('disabled')->nullable()->default(false);
      $table->unsignedBigInteger('menu_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('menu_id')
        ->references('idmenu')
        ->on('stablishments_menus');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(){
    Schema::dropIfExists('menus_products');
  }
}
