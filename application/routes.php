<?php
 
// application/routes.php
Route::controller('account');
Route::controller('post');
Route::controller('pin');
//Route::controller('image');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::get('/', function() {
    $posts = Post::order_by('updated_at', 'desc')->get();
    return View::make('pages.home')
        ->with('posts', $posts);
});

Route::get('uploads', function() {
    return Response::error('404');
});

Route::post('newidn/(:num)', array('before' => 'auth', 'do' => function($uid) {
    $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] 
        . '&host=' . $_SERVER['HTTP_HOST']);
    $ulogini = json_decode($s, true);

    $idnhash = md5($ulogini['identity']);
    $idents = Identity::where('identityhash', '=', $idnhash)->first();
    $user = User::find($uid);

    if($idents != NULL)
        return Redirect::to_action('account@show', array('uid' => $user->id));

    $new_ident = array(
        'user_id' => $user->id, 
        'first_name' => $ulogini['first_name'],
        'last_name' => $ulogini['last_name'],
        'identity' => $ulogini['identity'],
        'network' => $ulogini['network'],
        'identityhash' => md5($ulogini['identity']),
        'hidden' => false
    );

    $ident = new Identity($new_ident);
    $ident->save();
    return Redirect::to_action('account@show', array('uid' => $user->id));
}));

Route::get('(del|hide)/idn/(:num)', array('as' => 'idn', 'before' => 'auth', 'do' => function($a, $iid) {
    if($a == 'del')
        Identity::find($iid)->delete();

    if($a == 'hide')
    {
        $idn = Identity::find($iid);
        $idn->hidden = !$idn->hidden;
        $idn->save();
    }

    return Redirect::back();
}));

Route::post('social', function() {
    $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] 
        . '&host=' . $_SERVER['HTTP_HOST']);
    $ulogini = json_decode($s, true);
    $idnhash = md5($ulogini['identity']);
    $idents = Identity::where('identityhash', '=', $idnhash)->first();
    if($idents == NULL)
    {
        $socialarray = array(
            'firstname' => $ulogini['first_name'], 
            'lastname' => $ulogini['last_name'], 
            'network' => $ulogini['network'], 
            'identity' => $ulogini['identity'],
            'social' => 'true'
        );

        return Redirect::to_route('signup')->with(Input::old_input, $socialarray);
    }

    Auth::login($idents->user()->first()->id);

    return Redirect::to('/');
});

Route::get('(edit|new|delete)/post/(:num?)', array('as' => 'post', 'before' => 'auth', 'uses' => 'post@(:1)'));

Route::any('(edit|new|delete)/pin/(:num?)', array('as' => 'pin', 'before' => 'auth', 'uses' => 'pin@(:1)'));

Route::get('image/(:num)/(:any?)', function($id, $attrs = "") {
    $image = Image::find($id);

    $imagedata = Cache::get('image_' . $id . '_' . $attrs);
    if($imagedata == null) {
        if('' == $attrs)
            $imagedata = File::get($image->path . "/" . $image->name);
        else {
            $layer = PHPImageWorkshop\ImageWorkshop::initFromPath($image->path . "/" . $image->name);

            ob_start();

            if('image/jpeg' == $image->mime)
                imagejpeg($layer->getResult());

            if('image/png' == $image->mime)
                imagepng($layer->getResult());

            if('image/gif' == $image->mime)
                imagegif($layer->getResult());

            $imagedata = ob_get_contents();
            ob_end_clean();
        }

        Cache::put('image_' . $id . '_' . $attrs, $imagedata, 120);
    }

    return Response::make($imagedata, 200, array('content-type' => $image->mime));
});

Route::get('image/(:any)', function($shorturl) {
    echo "try get image by short url: " . $shorturl;
});

Route::get('comms/(:num)', function($postid) {
    $post = Post::find($postid);
    $comms = $post->comments()->get();
    return View::make('ajax.comms')
        ->with('comms', $comms)
        ->with('user', Auth::user());
});

Route::get('(delete)/comm/(:num)', array('before' => 'auth', 'as' => 'comm', 'do' => function($action, $commid){
    if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
    {
       Comment::find($commid)->delete();
    }

   return Redirect::back();
}));

Route::post('(edit|new)/comm/(:num?)', array('before' => 'auth', 'as' => 'comm', 'do' => function($action, $commid = 0) {
    // let's get the new comm from the POST data
    // this is much safer than using mass assignment
    $post = Post::find(Input::get('post_id'));
    if($post == NULL) {
        echo json_encode(array( 
                'status' => 0 ,
                'errors' => array("can't find post")
            ));

        return;
    }

    $new_comm = array(
        'body'      => Input::get('body'),
        'author_id' => Input::get('author_id'),
        'post_id'   => $post->id
    );
    // let's setup some rules for our new data
    // I'm sure you can come up with better ones
    $rules = array(
        'body'      => 'required'
    );
    // make the validator
    $v = Validator::make($new_comm, $rules);
    if ( $v->fails() )
    {
            echo json_encode(array( 
                    'status' => 0 ,
                    'errors' => array('empty comment')
                ));

            return;
    }

    if($action == 'new')
    // create the new comm
    {
        $comm = new Comment($new_comm);
        $comm->save();
        $post->touch(); // change updated_at field
    }
    else
    {
        $comm = Comment::find($commid);
        if($comm == NULL) {
            return Redirect::to_action('post@show', array('postid' => $post->id));
        }

        $comm = $new_comm;
        $comm->id = $commid;
        $comm->save();
    }

    echo json_encode(array( 
        'status' => 1 ,
    ));
}));

Route::any('admin/(:any)', array('before' => 'auth', 'uses' => 'admin@(:1)'));

Route::any('login', 'account@login');
Route::any('signup', array('as' => 'signup', 'uses' => 'account@signup'));
Route::any('remindpass', 'account@remindpass');

Route::get('logout', 'account@logout');



Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function($exception)
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

/*Route::filter('image', function($response)
{
    $response->header('Content-Type', 'image/jpeg');
});*/

