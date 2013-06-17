@layout('templates.main')

@section('morelinks')
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo/cq/cq.css" type="text/css" media="screen" />
    <script src="js/jquery.nivo.slider.pack.js" type="text/javascript"></script>
    <script src="js/editor.js" type="text/javascript"></script>
    <script src="js/uri.min.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            $('.inlinebg').inlinebackgrounds();
	        $('media').parseVideo();
        });

        (function($){
            $.fn.inlinebackgrounds = function() {
                $.each(this, function(i,t) {
                    var split = $(t).html().split('<br>');
                    var output = '';
                    $.each(split, function(i,o){
                        output += '<span>'+o+'</span>';
                        if (i < (split.length - 1)) {
                            output += '<br>';
                        }
                    });
                    $(t).html(output);
                });
            }
        })(jQuery);
    </script>
@endsection

@section('pinned')
<?php
$pins = Pin::where('showtime_start', '<', date('c'))
         ->where('showtime_end', '>', date('c'))->get();

if(count($pins) != 0) {

    echo '<div class="imagelayer">
            <div class="slider-wrapper theme-cq">
                <div id="slider" class="nivoSlider">';

    $linkdivs = '';
    foreach ($pins as $pinkey) {
        $linkuq = uniqid();
        $postpin = $pinkey->post()->first();
        if($postpin->img)
            echo '<img src="' . AuxImage::get_uri($postpin->img, $postpin->imgparam) 
                . '" title="#' . $linkuq . '"/>';
        else
            echo '<img src="img/x.png" title="#' . $linkuq . '"/>';

        $linkdivs .= '<div class="nivo-caption" id="' . $linkuq . '"><a href="' 
            . URL::to_action("post@show", array("postid" => $pinkey->post_id)) 
            . '">' . $pinkey->post()->first()->title . '</a></div>';
    }

    echo '</div></div>
          <script type="text/javascript">
            $(window).load(function() {
                $("#slider").nivoSlider({
                    effect: "random",
                    directionNav: false,
                    controlNav: true
                });
            });
            </script>'
            . $linkdivs .
    '</div>';
    echo '<div class="masklayer"><img src="img/m2.png"></div>';
}
else {
    echo '<div class="imagelayer"><img src="img/x.png"></div>';
    echo '<div class="masklayer" style="top: -215px;"><img src="img/m2.png"></div>';
}
?>
@endsection

@section('content')
    <div class='newsblock'>
<?php
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
?>
    </div>
<?php echo $posts->links(); ?>
@endsection
