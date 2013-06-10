<?php

class Create_Pins {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pins', function($table) {
			$table->increments('id');
			$table->integer('post_id')->unique();
			$table->date('showtime_start');
			$table->date('showtime_end');
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
		Schema::drop('pins');
	}

}
