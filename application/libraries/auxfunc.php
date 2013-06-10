<?php

class AuxFunc {
    static public function get_cookie_name_autologin() {
        return 'kvant.me.' . 'autologin';
    }

    static public function get_cookie_name_autologin_secret() {
        return 'kvant.me.' . 'autologin_secret';
    }

    static public function get_user_cookie_secret($user) {
    	Log::write('info', 'get_user_cookie_secret ' . $user->id . $user->password . '1qazXSW@'. Request::server('REMOTE_ADDR'));
        return md5($user->id . $user->password . '1qazXSW@'. Request::server('REMOTE_ADDR'));
    }

    static public function formatdate($datetime)
    {
        $dt = new DateTime($datetime);
        return $dt->format('d.m.Y');
    }

    static public function formattime($datetime)
    {
        $dt = new DateTime($datetime);
        return $dt->format('H:i');
    }
}
