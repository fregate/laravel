<?php

class AuxPwds {

static public function get_password($pwdname) {
    $pwdpath = path('storage').'pwds/'.$pwdname.'.pwd';
    $contents = File::get($pwdpath);
    if($contents == null)
	Log::warning($pwdpath.' not exist or empty');

//Log::info($pwdpath.' return '.rtrim($contents));

    return rtrim($contents);
}

}

