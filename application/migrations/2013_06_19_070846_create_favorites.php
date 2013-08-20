<?php

class Create_Favorites {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('favorites', function($table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('post_id')->nullable();
			$table->integer('article_id')->nullable();
			$table->timestamps();
		});
		//
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('favorites');
	}
}
