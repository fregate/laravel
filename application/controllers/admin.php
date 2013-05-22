
<?php

// application/controllers/admin.php
class Admin_Controller extends Base_Controller
{
    public $restful = true;

    public function get_index()
    {
        return View::make('admin.main');
    }

    public function get_pins()
    {
        return View::make('admin.pins');
    }

    public function post_pin()
    {
    }

    public function get_users()
    {
        return View::make('admin.users');
    }
}

