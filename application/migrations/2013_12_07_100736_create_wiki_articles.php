<?php

class Create_Wiki_Articles {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('wiki_articles', function($table) {
            $table->increments('id'); // unique ids for posts and wiki articles for unified comm system
 		    $table->string('uri', 128)->unique(); // unique identificator(link) for article - remove ids in link
  			$table->integer('category_id');
 		    $table->string('fl', 1); // for easier grouping by letters and numbers
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
		Schema::drop('wiki_articles');
	}

}
