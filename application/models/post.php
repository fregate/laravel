<?php

class Post extends Eloquent
{
public static $per_page = 42;

    public function author()
    {
        return $this->belongs_to('User', 'author_id');
    }

    public function comments()
    {
        return $this->has_many('Comment', 'post_id');
    }

    // public function url()
    // {
    // 	return URL::to_action('post@show')->with('post', $this->id);
    // }
}
