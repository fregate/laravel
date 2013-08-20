<?php

class Create_Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::create('users', function($table) {
		    $table->increments('id');
		    $table->string('nickname', 128);
		    $table->string('password', 64);
		    $table->string('email', 64)->unique();
		    $table->date('birthday');
		    $table->boolean('show_year');
		    $table->timestamps();
		});

		DB::table('users')->insert(array(
			'email' => 'admin@admin',
		    'nickname'  => 'Admin',
		    'password'  => Hash::make('pass')
		));
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}
}
