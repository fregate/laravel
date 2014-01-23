<?php

// table JSON ANY DATA. Allow store any custom data with group by different types for selection

class Create_Jany {

	/**
	 * Make changes to the database.
	 * 
	 * @return void
	 */
	public function up()
	{
        Schema::create('jany', function($table) {
            $table->increments('id');
    		$table->integer('uid'); // sets as page creator id (or current user?)
    		$table->string('token', 128); // it gonna be unique for different data
            $table->text('json'); // json data for the record
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
		Schema::drop('jany');
	}
}
