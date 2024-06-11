<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('publication', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('idPublication');
      $table->string('title')->index();
      $table->string('subtitle')->nullable()->index();
      $table->string('pseudonym')->nullable();
      $table->dateTime('datetime');
      $table->string('synopsis')->nullable();
      $table->text('description');
      $table->string('price')->nullable();
      $table->string('address')->nullable();
      $table->decimal('lat', 20, 17)->nullable();
      $table->decimal('lng', 20, 17)->nullable();
      $table->string('image')->nullable();
      $table->string('facebook')->nullable();
      $table->string('instagram')->nullable();
      $table->string('twitter')->nullable();
      $table->string('youtube')->nullable();
      $table->string('web')->nullable();
      $table->integer('likes')->nullable()->default(0);
      $table->integer('visits')->nullable()->default(0);
      $table->boolean('disabled')->nullable()->default(false);
      $table->boolean('disabledGlobal')->nullable()->default(false);
      
      $table->unsignedBigInteger('user_id')->nullable()->default(0);
      $table->unsignedBigInteger('municipio_id')->nullable();
      $table->unsignedBigInteger('section_id');
      //$table->boolean('deleted')->nullable()->default(false);
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('municipio_id')
        ->references('idmunicipio')
        ->on('municipios');

      $table->foreign('section_id')
        ->references('idsection')
        ->on('sections');

      $table->foreign('user_id')
        ->references('id')
        ->on('users');
    });

    /*
CREATE TABLE `publications` (
  `idPublication` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `subtitle` VARCHAR(255) NULL,
  `pseudonym` VARCHAR(255) NULL,
  `datetime` DATETIME NOT NULL,
  `synopsis` VARCHAR(255) NULL,
  `description` TEXT NOT NULL,
  `price` VARCHAR(255) NULL DEFAULT NULL,
  `address` VARCHAR(255) NULL DEFAULT NULL,
  `lat` DECIMAL(20, 17) NULL DEFAULT NULL,
  `lng` DECIMAL(20, 17) NULL DEFAULT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `facebook` VARCHAR(255) NULL DEFAULT NULL,
  `instagram` VARCHAR(255) NULL DEFAULT NULL,
  `twitter` VARCHAR(255) NULL DEFAULT NULL,
  `youtube` VARCHAR(255) NULL DEFAULT NULL,
  `web` VARCHAR(255) NULL DEFAULT NULL,
  `likes` INT NULL DEFAULT 0,
  `visits` INT NULL DEFAULT 0,
  `disabled` BOOLEAN NULL DEFAULT FALSE,
  `disabledGlobal` BOOLEAN NULL DEFAULT FALSE,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `municipio_id` BIGINT UNSIGNED NULL,
  `section_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idPublication`),
  INDEX `publications_title_index` (`title`),
  INDEX `publications_subtitle_index` (`subtitle`),
  CONSTRAINT `fk_publications_municipio_id` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`idmunicipio`),
  CONSTRAINT `fk_publications_section_id` FOREIGN KEY (`section_id`) REFERENCES `sections` (`idsection`),
  CONSTRAINT `fk_publications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB;
    */
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('publication');
  }
}
