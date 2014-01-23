<?php

class WikiArticle extends Eloquent
{
//public static $per_page = 21; // half from 42.
public static $table = "wiki_articles";

    public function author()
    {
        return User::find($this->vars()->first()->author_id)->first();
    }

    public function comments()
    {
        return $this->has_many('Comment', 'article_id');
    }

    public function categories()
    {
        $ccc = array();
        $cat = $this->belongs_to('WikiCategories', 'category_id');
        $ccc.push_back($cat);
        while($cat->parent != -1)
        {
            $cat = WikiCategories::find($cat->parent);
            $ccc[] = $cat;
        }

        return $ccc;
    }

    public function vars()
    {
        return $this->has_many('WikiVariants', 'article_id');
    }
}
