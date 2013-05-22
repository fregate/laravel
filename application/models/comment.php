<?php

class Comment extends Eloquent
{
    public function author()
    {
        return $this->belongs_to('User', 'author_id');
    }

    public function post()
    {
        return $this->belongs_to('Post', 'post_id');
    }

  //   static public function get_div_object($ccc)
  //   {
  //   	return get_div_fields($ccc->author()->first()->nickname, $ccc->body, $ccc->id);
  //   }

  //   static public function get_div_fields($author, $cbody, $cid)
  //   {
  //   	$retv = '<div class="comm"> <span>' 
  //  	    	. $cbody . '</span><span><h6> by ' 
  //  	    	. $author . '</h6></span> ';

		// if( !Auth::guest() && $user->has_any_role(array('admin', 'moderator')) )
		// {
	 //    	$retv += HTML::link_to_route('comm', 'Edit Comm', array('edit', $cid));
	 //    	$retv += HTML::link_to_route('comm', 'Delete Comm', array('delete', $cid));
		// }

	 //    $retv += '</div>';

		// return $retv;
  //   }
}
