<?php

class Add_Article_Id_To_Comments_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function($t) {
		    $t->integer('article_id');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('comments', function($t) {
			$t->drop_column('article_id');
        });
	}
}
