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
            'img'       => Input::get('imageid'),
            'imgparam'  => Input::get('imageparam'),
        );
    // let's setup some rules for our new data
    // I'm sure you can come up with better ones
        $rules = array(
            'title'     => 'required|min:1|max:128',
            'body'      => 'required',
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

        // create the new post
        $post = new Post($new_post);
        $post->save();

        return Redirect::to_action('post@show', array('postid' => $post->id));
    }

    public function post_edit($postid)
    {
        $p = Post::find($postid);
        $new_body = array(
            'body' => Input::get('bodyPost'),
        );

        $rules = array(
            'body' => 'required',
        );

        $v = Validator::make($new_body, $rules);
        if ( $v->fails() )
        {
            echo $p->body;
            return;
        }

        $p = Post::find($postid);
        $p->body = Input::get('bodyPost');
        $p->save();

        echo Input::get('bodyPost');
    }

    public function post_img($postid)
    {
        $p = Post::find($postid);

        $img = Input::get('imageid');
        $imgparam = Input::get('imageparam');

        $p = Post::find($postid);
        $p->img = $img;
        $p->imgparam = $img == 0 ? "" : $imgparam;
        $p->save();

        echo Input::get('body');
    }

    public function post_title($postid)
    {
        $p = Post::find($postid);
        $new_title = array(
            'title' => Input::get('title'),
        );
    // let's setup some rules for our new data
    // I'm sure you can come up with better ones
        $rules = array(
            'title' => 'required|min:1|max:128',
        );
        // make the validator
        $v = Validator::make($new_title, $rules);
        if ( $v->fails() )
        {
            echo $p->title;
            return;
        }

        $p->title = Input::get('title');
        $p->save();

        echo Input::get('title');
    }

    public function get_delete($postid)
    {
        //User::find(Auth::user()->id)->has_any_role(array('admin', 'moderator'))
        if( Auth::guest() || Auth::user()->has_any_role(array('admin', 'moderator')) )
        {
            $post = Post::find($postid);
            $post->comments()->delete();

            Pin::where('post_id', '=', $post->id)->delete();

            $post->delete();
            return Redirect::to('/');
        }
        else
           return Redirect::back();
    }
}
