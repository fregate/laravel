<?php

class WikiArticle_Controller extends Base_Controller
{
    public $restful = true;

    public function get_index()
    {
        return View::make('wiki.dashboard');
    }

    public function get_show($waid, $version = 0)
    {
   	 $wa = WikiArticle::find($waid);
	 return View::make('wiki.article')
	         ->with('article', $wa)
             ->with('version', $version);
    }

    public function get_showany($wuri, $version = 0)
    {
     $wa = WikiArticle::where('uri', '=', $wuri)->first();
     if($wa == NULL)
     {
        $wa = new WikiArticle404;
        $version = 0;
     }

     return View::make('wiki.article')
             ->with('article', $wa)
             ->with('version', $version);
    }

    public function get_new()
    {
        return View::make('wiki.wnew')->with('user', Auth::user());
    }

    public function post_new()
    {
	    $new_a = array(
            'title'     => Input::get('title'),
            'body'      => Input::get('body'),
            'author_id' => Input::get('author_id'),
            'category'  => Input::get('category'),
        );
    // let's setup some rules for our new data
    // I'm sure you can come up with better ones
        $rules = array(
            'title'     => 'required|min:1|max:128',
            'body'      => 'required',
            'category'  => 'required',
            'author_id' => 'required'
        );
        // make the validator
        $v = Validator::make($new_a, $rules);
        if ( $v->fails() )
        {
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('wiki/new')
                    ->with('user', Auth::user())
                    ->with_errors($v)
                    ->with_input();
        }

        // create the new post
        $tr = IoC::resolve('translit');
        $na = array(
            'category_id' => $new_a['category'],
            'fl' => strtolower(substr($new_a['title'], 0, 1)),
            'uri' => substr($tr->translit_title($new_a['title']), 0, 128)
        );
        $wa = new WikiArticle($na);
        $wa->save();

        $nv = array(
            'article_id' => $wa->id,
            'title' => $new_a['title'],
            'body' => $new_a['body'],
            'author_id' => $new_a['author_id']
        );

        $wv = new WikiArticleVariant($nv);
        $wv->save();

        return Redirect::to_action('wikiarticle@showany', array($wa->uri));
    }

    public function post_edit($wa)
    {
    	echo "edit article! " . $postid;
    }

    public function post_title($wa)
    {
        echo "edit article title! " . $postid;
    }

    public function get_delete($waid)
    {
        //User::find(Auth::user()->id)->has_any_role(array('admin', 'moderator'))
        if( Auth::guest() || Auth::user()->has_any_role(array('admin', 'moderator')) )
        {
            $wa = WikiArticle::find($waid);
            $wa->comments()->delete();

            Pin::where('post_id', '=', $wa->id)->delete();

            $wa->delete();
            return Redirect::to('wiki/');
        }
        else
           return Redirect::back();
    }

    public function post_blame() // return to previous version and remove all next versions
    {
        $waid = Input::get('article_id');
        $toversion = Input::get('article_version_id');
    }
}
