
<?php

class Pin extends Eloquent
{
    public function post()
    {
        return $this->belongs_to('Post', 'post_id');
    }

    static public function get_tr($ppp, $convert = false)
    {
        if($convert)
        {
            $ts = new DateTime($ppp->showtime_start);
            $te = new DateTime($ppp->showtime_end);
        }
        else
        {
            $ts = $ppp->showtime_start;
            $te = $ppp->showtime_end;
        }

    	return '<tr><td>' 
             . $ppp->post_id . '</td><td>' 
             . $ppp->post()->first()->title . '</td><td><image src="'
             . AuxImage::get_uri($ppp->post()->first()->img) . '"/></td><td>' 
             . $ts->format('d-m-Y') . '</td><td>' 
             . $te->format('d-m-Y') . '</td><td>'
             . HTML::link_to_action('pin@delete', 'Del', array($ppp->post_id)) .'</td></tr>';
    }
}
