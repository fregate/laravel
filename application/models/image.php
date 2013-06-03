<?php

class Image extends Eloquent
{
    public function uploader()
    {
        return $this->belongs_to('User', 'user_id');
    }
}
