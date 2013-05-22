<?php

class Create_Roles {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function($table)
		{
			$table->increments('id');
			$table->string('name', 16)->unique();
			$table->timestamps();
		});

		Role::create(array(
			'id' => 1,
			'name' => 'admin'
		));

		Role::create(array(
			'id' => 2,
			'name' => 'readonly'
		));

		Role::create(array(
			'id' => 3,
			'name' => 'moderator'
		));
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles');
	}
}
