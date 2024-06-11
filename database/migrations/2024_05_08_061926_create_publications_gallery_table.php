<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsGalleryTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('publications_gallery', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('id');
      $table->string('name')->nullable();
      $table->string('description')->nullable();
      $table->string('path')->nullable();
      $table->string('image')->nullable();
      $table->unsignedSmallInteger('order')->nullable();
      $table->boolean('disabled')->nullable()->default(false);
      $table->unsignedBigInteger('publication_id');
      //$table->boolean('deleted')->nullable()->default(false);
      $table->softDeletes();
      $table->timestamps();

      $table->foreign('publication_id')
        ->references('id')
        ->on('publications');
    });

    /*
CREATE TABLE `publications_gallery` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(90) NULL,
  `description` VARCHAR(250) NULL,
  `path` VARCHAR(250) NOT NULL,
  `image` VARCHAR(90) NOT NULL,
  `order` INT(3) NULL,
  `disabled` TINYINT(1) NULL DEFAULT 0,
  `publication_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `publications_gallery` 
ADD INDEX `publications_gallery_publication_id_idx` (`publication_id` ASC);

ALTER TABLE `publications_gallery` 
ADD CONSTRAINT `publications_gallery_publication_id_fk`
  FOREIGN KEY (`publication_id`)
  REFERENCES `publications` (`idPublication`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

    */
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('publications_gallery');
  }
}
