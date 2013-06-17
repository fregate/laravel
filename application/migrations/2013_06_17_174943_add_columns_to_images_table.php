<?php

class Add_Columns_To_Images_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('images', function($t) {
		    $t->integer('user_id'); // who upload the image
		    $t->text('comm'); // remove comm for example
		}
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('images', function($t) {
            $t->drop_column('user_id');
            $t->drop_column('comm');
        });
    }
}
