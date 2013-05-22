<?php

class Create_Comments {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function($table) {
		    $table->increments('id');
		    $table->text('body');
		    $table->integer('author_id');
		    $table->integer('post_id');
		    $table->timestamps();
		});		//
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comments');
	}

}