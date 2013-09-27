<?php

class Add_Article_Id_To_Wiki_Article_Variants {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::table('wiki_article_variants', function($t) {
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
            Schema::table('wiki_article_variants', function($t) {
                            $t->drop_column('article_id');
            });
	}
}
