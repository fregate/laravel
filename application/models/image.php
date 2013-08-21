<?php

class Image extends Eloquent
{
public static $per_page = 21; // half from 42.

    public function uploader()
    {
        return $this->belongs_to('User', 'user_id');
    }
}
