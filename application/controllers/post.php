<?php

class Post_Controller extends Base_Controller
{
    public $restful = true;

    public function get_index()
    {
        echo "action_index";
    }
    
    public function get_show($postid)
    {
   	    $post = Post::find($postid);
	    return View::make('pages.full')
	           ->with('post', $post);
    }

    public function get_new()
    {
        return View::make('pages.newpost')->with('user', Auth::user());
    }

    public function post_new()
    {
	    $new_post = array(
            'title'     => Input::get('title'),
            'body'      => Input::get('body'),
            'author_id' => Input::get('author_id'),
            AuxImage::get_field()     => Input::file(AuxImage::get_field()),
        );
    // let's setup some rules for our new data
    // I'm sure you can come up with better ones
        $rules = array(
            'title'     => 'required|min:3|max:128',
            'body'      => 'required',
            AuxImage::get_field()    => 'image'
        );
        // make the validator
        $v = Validator::make($new_post, $rules);
        if ( $v->fails() )
        {
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('post/new')
                    ->with('user', Auth::user())
                    ->with_errors($v)
                    ->with_input();
        }
        unset($new_post['uimage']);

        $imgid = AuxImage::make(Input::file('uimage'));
        $new_post['img'] = $imgid;

        // create the new post
        $post = new Post($new_post);
        $post->save();

        return Redirect::to_action('post@show', array('postid' => $post->id));
    }

    public function get_edit($postid)
    {
    	echo "edit post! " . $postid;
    }

    public function get_delete($postid)
    {
        //User::find(Auth::user()->id)->has_any_role(array('admin', 'moderator'))
        if( Auth::guest() || Auth::user()->has_any_role(array('admin', 'moderator')) )
        {
            $post = Post::find($postid);
            $post->comments()->delete();

            $pin = Pin::where('post_id', '=', $post->id)->get();
            if($pin != null)
                $pin->delete();

            $post->delete();
            return Redirect::to('/');
        }
        else
           return Redirect::back();

    }
}
