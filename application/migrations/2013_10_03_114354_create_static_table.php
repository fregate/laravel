<?php

class Create_Static_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::create('static', function($table) {
                    $table->increments('id');
                    $table->string('suri', 64)->unique();
                    $table->integer('author_id');
                    $table->string('title', 64);
                    $table->text('meta');
                    $table->text('scripts');
                    $table->text('styles');
                    $table->text('content');
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
            Schema::drop('static');
	}
}
