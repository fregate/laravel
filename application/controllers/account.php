
<?php

// application/controllers/account.php
class Account_Controller extends Base_Controller
{
    protected function send_mail($user, $subj, $body_paintext) {
        Bundle::start('swiftmailer');
        $mailer = IoC::resolve('mailer');

        $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('site@kvant.in')
	    ->setPassword(AuxPwds::get_password('mailaccount'));

        $mailer = Swift_Mailer::newInstance($transporter);

    // To use the ArrayLogger
        $logger = new Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

        $message = Swift_Message::newInstance($subj)
                ->setFrom(array('info@kvant.in' => 'Клуб Квант'))
                ->setTo(array($user->email => $user->nickname))
                ->setBody($body_paintext);
        $numSent = $mailer->send($message, $failures);
        if ($numSent < 1) 
            dd($logger->dump()); //see logs
    }

    public $restful = true;

    public function get_index()
    {
        return Redirect::to('/');
    }

    public function get_show($uid)
    {
        $user = User::find($uid);
        return View::make('pages.userprofile')->with('user', $user);
    }

    public function post_update() // ajax
    {
        $u = User::find(Input::get('user_id'));
        if($u == null)
        {
            return json_encode(array(
                'status' => 0,
                'message' => "no user " . Input::get('user_id') . " exists"
            ));
        }

        if(Auth::guest() || Auth::user()->id != $u->id)
        {
            return json_encode(array(
                'status' => 0,
                'message' => 'no rights to perform this action',
                'input' => Input::all()
            ));
        }

        if(!Hash::check(Input::get('oldpassword'), $u->password))
        {
            return json_encode(array(
                'status' => 0,
                'message' => 'wrong current password',
                'input' => Input::all()
            ));
        }

	    $new_account = array('password' => Input::get('newpassword'));
        if($u->id != 1)
        {
            $new_account['email'] = Input::get('newemail');
            $new_account['birthday'] = Input::get('birthday');
            $new_account['show_year'] = Input::get('show_birth_year') == '1';
            $new_account['nickname'] = Input::get('newname');

            $rules = array(
                'nickname'  => 'required|min:3|max:128',
                'email'  => 'required|min:3|max:64'
            );

            $v = Validator::make($new_account, $rules);
            if($v->fails())
            {
                return json_encode(array(
                    'status' => 0,
                    'message' => 'input errors',
                    'input' => Input::all()
                ));
            }

            $uemail = User::where('email', '=', $new_account['email'])->where('id', '!=', $u->id)->first();
            if($uemail != null)
            {
                return json_encode(array(
                    'status' => 0,
                    'message' => 'email already used by another user',
                    'input' => Input::all()
                ));
            }

            $u->email = $new_account['email'];
            $u->nickname = $new_account['nickname'];
            $u->birthday = DateTime::createFromFormat("d-m-Y|", $new_account['birthday']);
            $u->show_year = $new_account['show_year'];
        }

        if($new_account['password'] != '') {
            $u->password = Hash::make($new_account['password']);
            $this->send_mail($u, "Смена пароля на сайте", "Приветствуем!\n
                Вы изменили пароль для входа на сайт Клуба Квант!\n
                Теперь он вот такой: ". $new_account['password'] . "\n
                Заходите к нам по-чаще!\n\n
                Ваш Клуб Квант");
        }

        $u->save();

	    return json_encode(array( 'status' => 1, 'message' => 'information updated successfully' ));
    }

    public function get_login()
    {
        return View::make('pages.login');
    }

    public function post_login()
    {
        $userdata = array(
            'username' => strtolower(Input::get('email')),
            'password' => Input::get('password'),
	    'remember' => Input::get('rememberme')
        );

        if ( Auth::attempt($userdata) )
        {
            $userdata['recaptcha'] = Input::get('recaptcha_response_field');
            $rules = array(
                'recaptcha' => 'recaptcha:6LePcOASAAAAAIqDe2sjBrp1RmAQJL70xLJn7GNs|required',
            );

            // make the validator
            $v = Validator::make( $userdata, $rules );
            if ( $v->fails() )
            {
                Auth::logout();

                return Redirect::to('login')
                        ->with('captcha_error', true)
                        ->with_input();
            }

            return Redirect::to('/')->with('rememberuser', Input::get('rememberme') == '1');
        }
        else
        {
            return Redirect::to('login')
                ->with('login_errors', true)
                ->with_input();
        }
    }

    public function get_logout()
    {
        Log::write('info', 'autologin cookies forget');
    	Cookie::forget(AuxFunc::get_cookie_name_autologin());
    	Cookie::forget(AuxFunc::get_cookie_name_autologin_secret());

        Auth::logout();
        return Redirect::to('/');
    }

    public function get_signup()
    {
        return View::make('pages.signup');
    }

    public function post_signup()
    {
        $new_account = array(
            'nickname' => Input::get('firstname') . " " . Input::get('lastname'),
            'password' => Input::get('password'),
            'recaptcha' => Input::get('recaptcha_response_field')
        );

        if(Input::get('email') == null || Input::get('email') == '')
            $new_account['email'] = uniqid() . '@' . uniqid();
        else
            $new_account['email'] = Input::get('email');

        $rules = array(
            'nickname'  => 'required|min:3|max:128',
            'recaptcha' => 'recaptcha:6LePcOASAAAAAIqDe2sjBrp1RmAQJL70xLJn7GNs|required',
        );

        if((Input::get('social') == 'true' && Input::get('email') != '') || Input::get('social') == 'false')
        {
            $rules['email']  = 'required|min:3|max:64';
            $rules['password'] = 'required';
        }

        // make the validator
        $v = Validator::make($new_account, $rules);
        if ( $v->fails() )
        {
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to_action('account@signup')
                    ->with('input_errors', true)
                    ->with_input();
        }

        unset($new_account['recaptcha']);

        $new_account['password'] = Hash::make(Input::get('password'));

        $testemail = User::where('email', '=', $new_account['email'])->first();
        if($testemail != null) {
            return Redirect::to_action('account@signup')
                    ->with('input_errors', true)
                    ->with_input();
        }

        // create the new post
        $account = new User($new_account);
        $account->save();

        $new_ident = array(
            'user_id' => $account->id, 
            'first_name' => Input::get('firstname'),
            'last_name' => Input::get('lastname'),
            'identity' => Input::get('identity'),
            'network' => Input::get('network'),
            'identityhash' => md5(Input::get('identity')),
            'hidden' => false
        );

        if(Input::get('network') == 'club.quant')
        {
            $new_ident['identity'] = 'club.quant/' . $account->id;
            $new_ident['identityhash'] = md5($new_ident['identity']);
        }

        $indent = new Identity($new_ident);
        $indent->save();

        Auth::login($account->id);

        if(Input::get('social') != 'true') {
            $this->send_mail($account, "Регистрация на сайте Клуба Квант", 
                "Поздравляем!\n
                Вы зарегистрировались на сайте Клуба Квант!\n
                Ваш логин: ".$account->email."\n
                Ваш пароль: ".Input::get('password')."\n
                Заходите к нам по-чаще! Мы будем рады!\n\n
                Ваш Клуб Квант");
        }

        return Redirect::to_action('account@show', array('uid' => $account->id));
    }

    public function get_remindpass()
    {
        return View::make('pages.remindpass');
    }

    public function post_remindpass()
    {
        $find_account = array(
            'email' => Input::get('email'),
            'recaptcha' => Input::get('recaptcha_response_field')
        );

        $email_rule = array(
            'email'  => 'required|min:3|max:64'
        );

        // make the validator
        $v = Validator::make($find_account, $email_rule);
        if ( $v->fails() )
        {
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to_action('account@remindpass')
                ->with('input_errors', true)
                ->with_input();
        }

        $captcha_rule = array(
            'recaptcha' => 'recaptcha:6LePcOASAAAAAIqDe2sjBrp1RmAQJL70xLJn7GNs|required'
        );

        $v = Validator::make($find_account, $captcha_rule);
        if ( $v->fails() )
        {
            return Redirect::to_action('account@remindpass')
                ->with('captcha_errors', true)
                ->with_input();
        }

        $u = User::where('email', '=', $find_account['email'])->first();
        if($u == null)
        {
            return Redirect::to_action('account@remindpass')
                ->with('email_errors', true)
                ->with_input();
        }
        else
        {
            $newpass = uniqid();
            $u->password = Hash::make($newpass);
            $u->save();

            $this->send_mail($u, 'Сброс пароля на сайте', 
                "Приветствуем!\n
                Мы долго думали, какой же пароль вам больше всего подойдет и решили, 
                что именно вот этот: " . $newpass . "\n
                Если этот пароль все-таки не понравился, то его всегда можно сменить в своем профиле!\n
                Заходите к нам по-чаще!\n\n
                Ваш Клуб Квант");
        }

        return Redirect::to_action('account@remindpass')
            ->with('everything_ok', true);
    }

    public function get_getroles()
    {
        $roles = Role::all();

        foreach ($roles as $r) {
            $rns[] = $r->name;
        }

        return json_encode(array(
            'status' => 1,
            'tags' => $rns
        ));
    }

    public function get_checkrole($rolename)
    {
        if(Auth::guest() || !Auth::user()->has_role('admin'))
        {
            return json_encode(array(
                'status' => 0,
                'error' => 'no rights to perform this action'
            ));
        }

        $r = Role::where('name', '=', $rolename)->first();
        if($r == null)
        {
            return json_encode(array(
                'status' => 0,
                'error' => "no role '" . $rolename . "' exists"
            ));
        }

        return json_encode(array(
            'status' => 1,
            'error' => "role '" . $rolename . "' exist"
        ));
    }

    public function get_addrole($uid, $rolename)
    {
        if(Auth::guest() || !Auth::user()->has_role('admin'))
        {
            return json_encode(array(
                'status' => 0,
                'error' => 'no rights to perform this action'
            ));
        }

        $r = Role::where('name', '=', $rolename)->first();
        if($r == null)
        {
            return json_encode(array(
                'status' => 0,
                'error' => "no role '" . $rolename . "' exists"
            ));
        }

        $u = User::find($uid);
        if($u == null)
        {
            return json_encode(array(
                'status' => 0,
                'error' => "no user " . $uid . " exists"
            ));
        }

        $u->roles()->attach($r->id);

        return json_encode(array(
            'status' => 1,
            'error' => "role '" . $rolename . "' added to user " . $uid
        ));
    }

    public function get_delrole($uid, $rolename)
    {
        if(Auth::guest() || !Auth::user()->has_role('admin'))
        {
            return json_encode(array(
                'status' => 0,
                'error' => 'no rights to perform this action'
            ));
        }

        $r = Role::where('name', '=', $rolename)->first();
        if($r == null)
        {
            return json_encode(array(
                'status' => 0,
                'error' => "no role '" . $rolename . "' exists"
            ));
        }

        $u = User::find($uid);
        if($u == null)
        {
            return json_encode(array(
                'status' => 0,
                'error' => "no user " . $uid . " exists"
            ));
        }

        if($r->id == 1 && $u->id == 1)
        {
            return json_encode(array(
                'status' => 0,
                'error' => "can't remove admin rights from admin. so stupid move"
            ));
        }

        $u->roles()->where('role_id', '=', $r->id)->delete();

        return json_encode(array(
            'status' => 1,
            'error' => "role '" . $rolename . "' from user " . $uid . " removed"
        ));
    }
}
