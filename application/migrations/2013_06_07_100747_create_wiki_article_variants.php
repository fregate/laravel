<?php

class Create_Wiki_Article_Variants {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('wiki_article_variants', function($table) {
                    $table->increments('id');
                    $table->string('title', 128);
                    $table->text('body');
                    $table->integer('author_id');
                    $table->string('comm', 256);
                    $table->timestamps();
                });
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('wiki_article_variants');
	}

}
