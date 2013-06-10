<?php

class Create_Wiki_Categories {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('wiki_categories', function($table) {
                    $table->increments('id');
		    		$table->integer('parent')->nullable();
                    $table->string('title', 128);
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
		Schema::drop('wiki_categories');
	}

}
