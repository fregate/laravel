@layout('templates.profile')

@section('morelinks')
<script type="text/javascript" src="js/uri.min.js"></script>
<script type="text/javascript" src="js/editor.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $("#liposts").addClass("active");
  $('media').parseVideo();
});
</script>
@endsection

@section('profilesection')
<?php
$posts = $user->posts()->order_by('created_at', 'desc')->paginate();
//$posts = Post::order_by('created_at', 'desc')->where('author_id', '=', $user->id)->paginate();
foreach ($posts->results as $post) {
    echo "<div class='postentry'>";

    if ( $post->img ) // post with image or not
        echo "<div class='postimage' style='background: url(" . AuxImage::get_uri($post->img, $post->imgparam) . ")'><h3 class='inlinebg'>";
    else
        echo "<h3>";

    echo "<a href='" . URL::to_action('post@show', array($post->id)) . "'>" . $post->title . "</a>";

    if ( $post->img ) { // close post with|without image
        echo "</h3></div>";
    }
    else {
        echo "</h3>";
    }

    echo "<p>" . $post->body . "</p>"; // post body 

    echo "<div class='posttimestamp'>от " . HTML::link_to_action('account@show', $post->author()->first()->nickname, array('uid' => $post->author()->first()->id)) . ", " 
        . AuxFunc::formatdate($post->created_at) . " в " . AuxFunc::formattime($post->created_at) 
        . " | <a href=" . URL::to_action('post@show', array($post->id)) . "><div class='commanchor'><span class='commcount'>" . $post->comments()->count() . "</span></div></a></div>";

    echo "</div>"; // postentry
}

echo $posts->links();
?>
@endsection
