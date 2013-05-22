
<?php

// application/controllers/account.php
class Account_Controller extends Base_Controller
{
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

    public function get_login()
    {
        return View::make('pages.login');
    }

    public function post_login()
    {
        $userdata = array(
            'username' => Input::get('email'),
            'password' => Input::get('password'),
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

            return Redirect::to('/');
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
            'password' => Hash::make(Input::get('password')),
            'recaptcha' => Input::get('recaptcha_response_field')
        );

        if(Input::get('email') == null || Input::get('email') == '')
            $new_account['email'] = uniqid();
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
            'hidden' => Input::get('hideidn')
        );

        if(Input::get('network') == 'club.quant')
        {
            $new_ident['identity'] = 'club.quant/' . $account->id;
            $new_ident['identityhash'] = md5($new_ident['identity']);
        }

        $indent = new Identity($new_ident);
        $indent->save();

        Auth::login($account->id);

        return Redirect::to_action('account@show', array('uid' => $account->id));
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
