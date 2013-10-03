<?php

class StaticPage_Controller extends Base_Controller
{
    public $restful = true;

    public function get_index()
    {
        return Redirect::to('/');
    }

    public function get_new()
    {
    }

    public function get_del($pid)
    {
        Favorites::find($pid)->delete();

        return Redirect::to('/');
    }
}
