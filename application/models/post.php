<?php

class Post extends Eloquent
{
    public function author()
    {
        return $this->belongs_to('User', 'author_id');
    }

    public function comments()
    {
        return $this->has_many('Comment');
    }

    public function identities()
    {
        return $this->has_many('Identity');
    }

    public function url()
    {
    	return URL::to_action('post@show')->with('post', $this->id);
    }
}
