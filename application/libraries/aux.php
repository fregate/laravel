<?php

class Aux {

        static public function get_cookie_name_autologin() {
                return URL::base() . 'autologin';
        }

        static public function get_cookie_name_autologin_secret() {
                return URL::base() . 'autologin_secret';
        }

        static public function get_user_cookie_secret($user) {
                return md5($user->id . $user->passhash . '1qazXSW@'. Request::server('REMOTE_ADDR'));
        }

}


