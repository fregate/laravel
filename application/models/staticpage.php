<?php

class StaticPage extends Eloquent
{
	public static $table = 'static';

    public function author()
    {
        return $this->belongs_to('User', 'author_id');
    }
}
