<?php

class Pin_Controller extends Base_Controller
{
    public $restful = true;

    public function get_index()
    {
        return View::make('admin.pins');
    }

    public function post_new()
    {
        $new_pin = array(
            'post_id'         => Input::get('postid'),
            'showtime_start'  => Input::get('dpd_start'),
            'showtime_end'    => Input::get('dpd_end'),
        );

        $rules = array(
            'post_id'          => 'required',
            'showtime_start'   => 'required',
            'showtime_end'     => 'required',
        );

        $v = Validator::make($new_pin, $rules);
        if ( $v->fails() )
        {
            echo json_encode(array( 
                    'status' => 0 ,
                    'errors' => 'invalid fields'
                ));
        }
        else
        {
            $new_pin['showtime_start'] = DateTime::createFromFormat("d-m-Y|", $new_pin['showtime_start']);
            $new_pin['showtime_end'] = DateTime::createFromFormat("d-m-Y|", $new_pin['showtime_end']);
            $pin = new Pin($new_pin);
            $pin->save();

            echo json_encode(array(
                'status' => 1,
                'html' => Pin::get_tr($pin)
            ));
        }
    }

    public function get_edit($pid)
    {
    	echo "edit pin! ".$pid;
    }

    public function get_delete($pid)
    {
        Pin::where('post_id', '=', $pid)->delete();

        return View::make('admin.pins');
    }
}
