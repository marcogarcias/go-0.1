<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTagsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('publications_tags', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->bigIncrements('id');
      $table->unsignedBigInteger('publication_id');
      $table->unsignedBigInteger('tag_id');
      //$table->boolean('deleted')->nullable()->default(false);
      $table->softDeletes();
      $table->timestamps();

      $table->foreign('publication_id')
        ->references('idPublication')
        ->on('publications');

      $table->foreign('tag_id')
        ->references('idtag')
        ->on('tags');
    });

    /*
CREATE TABLE `publications_tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `publication_id` BIGINT UNSIGNED NOT NULL,
  `tag_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `c7x.6ee.mywebsitetransfer.com_1674567193`.`publications_tags` 
ADD INDEX `publications_tags_publication_id_foreign` (`publication_id` ASC),
ADD INDEX `publications_tags_tag_id_foreign` (`tag_id` ASC);

ALTER TABLE `c7x.6ee.mywebsitetransfer.com_1674567193`.`publications_tags` 
ADD CONSTRAINT `publications_tags_publication_id_foreign`
  FOREIGN KEY (`publication_id`)
  REFERENCES `c7x.6ee.mywebsitetransfer.com_1674567193`.`publications` (`idPublication`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `publications_tags_tag_id_foreign`
  FOREIGN KEY (`tag_id`)
  REFERENCES `c7x.6ee.mywebsitetransfer.com_1674567193`.`tags` (`idtag`)
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
    Schema::dropIfExists('publications_tags');
  }
}
