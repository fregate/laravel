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
		if(version_compare(PHP_VERSION, '5.3.7') >= 0) {
            $new_pin['showtime_start'] = DateTime::createFromFormat("d-m-Y|", $new_pin['showtime_start']);
            $new_pin['showtime_end'] = DateTime::createFromFormat("d-m-Y|", $new_pin['showtime_end']);
		} else { // in 5.3.6 don't work | assigment
            $new_pin['showtime_start'] = DateTime::createFromFormat("d-m-Y", $new_pin['showtime_start']);
            $new_pin['showtime_end'] = DateTime::createFromFormat("d-m-Y", $new_pin['showtime_end']);
Log::info($new_pin['showtime_start']->format('Y-m-d'));
		}
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
