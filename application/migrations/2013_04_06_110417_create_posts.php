<?php

class Create_Posts {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function($table) {
		    $table->increments('id'); // unique ids for posts and wiki articles
		    $table->string('title', 128);
		    $table->text('body');
		    $table->integer('author_id');
		    $table->date('showtime_start'); // use for auto calendar
		    $table->date('showtime_end');
		    $table->integer('img');
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
		Schema::drop('posts');
	}
}
