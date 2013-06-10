<?php

class Create_Images {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function($table) {
		    $table->increments('id');
		    $table->string('path', 256);
		    $table->string('name', 128);
		    $table->string('shorturl', 64)->unique();
		    $table->integer('size');
		    $table->integer('sx');
		    $table->integer('sy');
		    $table->string('mime', 32);
		    $table->integer('user_id'); // who upload the image
		    $table->text('comm'); // remove comm for example
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
		Schema::drop('images');
	}
}
