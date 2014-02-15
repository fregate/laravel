<?php

class WikiArticleVariant extends Eloquent
{
public static $table = "wiki_article_variants";

    public function article()
    {
        return $this->belongs_to('WikiArticle', 'article_id');
    }

    public function author()
    {
        return $this->belongs_to('User', 'author_id');
    }
}
