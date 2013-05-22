<?php

class Identity extends Eloquent
{
	public static $table = 'identities';
	
    public function user()
    {
        return $this->belongs_to('User', 'user_id');
    }

 //    public function set_identityhash($idntohash)
	// {
 //    	$this->set_attribute('identityhash', md5($idntohash));
	// }
}
