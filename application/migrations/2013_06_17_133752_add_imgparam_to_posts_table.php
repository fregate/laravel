<?php

class Add_Imgparam_To_Posts_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function($t) {
		    $t->string('imgparam', 256); // and parameters. like base64(json({x, y, cx, cy})). 
    		// if empty - crop to header original, like 
    		// {"x":0,"y":0,"width":min(PICx,620),"height":min(PICy,215),"framex":620,"framey":215}
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('posts', function($t) {
			$t->drop_column('imgparam');
        });
    }
}
