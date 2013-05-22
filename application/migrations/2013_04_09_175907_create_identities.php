<?php

class Create_Identities {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('identities', function($table) {
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->string('first_name', 64);
		    $table->string('last_name', 64);
		    $table->string('identity', 128);
		    $table->string('identityhash', 32)->unique();
		    $table->string('network', 64);
		    $table->boolean('hidden');
		    $table->timestamps();
		});

		DB::table('identities')->insert(array(
			'user_id' => 1,
			'identity' => 'club.quant/1',
			'network' => 'club.quant',
			'identityhash' => md5('club.quant/1'),
			'hidden' => false
		));
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('identities');
	}
}
