<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStablishmentsMenusPdfTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(){
    Schema::create('stablishments_menus_pdf', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idMenuPdf');
      $table->string('name')->nullable();
      $table->string('description')->nullable();
      $table->string('path')->nullable();
      $table->string('pdf')->nullable();
      $table->unsignedSmallInteger('order')->nullable();
      $table->boolean('disabled')->nullable()->default(false);
      $table->unsignedBigInteger('stablishment_id');
      $table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();

      $table->foreign('stablishment_id')
        ->references('idstablishment')
        ->on('stablishments');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(){
    Schema::dropIfExists('stablishments_menus_pdf');
  }

  /*
CREATE TABLE 

`c7x.6ee.mywebsitetransfer.com_1674567193`.`stablishments_menus_pdf` (
  `idMenuPdf` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NULL,
  `description` VARCHAR(250) NULL,
  `path` VARCHAR(250) NULL,
  `pdf` VARCHAR(250) NULL,
  `order` SMALLINT(5) NULL,
  `disabled` TINYINT(1) NULL DEFAULT 0,
  `stablishment_id` BIGINT UNSIGNED NOT NULL,
  `deleted` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`idMenuPdf`));

  ALTER TABLE `c7x.6ee.mywebsitetransfer.com_1674567193`.`stablishments_menus_pdf` 
  CHARACTER SET = utf8 , COLLATE = utf8_general_ci , ENGINE = InnoDB ;
  
  ALTER TABLE `c7x.6ee.mywebsitetransfer.com_1674567193`.`stablishments_menus_pdf` 
  ADD INDEX `stablishments_menus_pdf_stablishment_id_idx` (`stablishment_id` ASC);

  ALTER TABLE `c7x.6ee.mywebsitetransfer.com_1674567193`.`stablishments_menus_pdf` ALTER INDEX `stablishments_menus_pdf_stablishment_id_idx` INVISIBLE;



  ALTER TABLE `c7x.6ee.mywebsitetransfer.com_1674567193`.`stablishments_menus_pdf` 
  ADD CONSTRAINT `stablishments_menus_pdf_stablishment_id_fk`
    FOREIGN KEY (`stablishment_id`)
    REFERENCES `c7x.6ee.mywebsitetransfer.com_1674567193`.`stablishments` (`idstablishment`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;

  */
}
