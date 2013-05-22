@layout('templates.main')

@section('morelinks')
<style>
.pinned {
    width: 800px;
    z-index: 2;
    position: relative;
}

.postheader {
  width:500px;
  height: 300px;
}

</style>
@endsection

@section('content')
@if (Pin::first() != null)
  <?php
    $pins = Pin::where('showtime_start', '<', date('c'))
             ->where('showtime_end', '>', date('c'))->get();
//    $pins = Pin::all();

    if(count($pins) != 0) {

   echo '<script src="js/jquery.nivo.slider.js" type="text/javascript"></script>
   <div class="pinned">
        <div class="slider-wrapper theme-light">
            <div class="ribbon"></div>
    <div id="slider" class="nivoSlider">';

    foreach ($pins as $pinkey) {
//        var_dump($pinkey);
//        $post = $pinkey->post()->first();
        echo '<a href="' . URL::to_action('post@show', array('postid' => $pinkey->post_id)) 
            . '"><img src="' . AuxImage::get_uri($pinkey->post()->first()->img) 
            . '" alt="" title="' . $pinkey->post()->first()->title . '"/></a>';
    }
        // <a href="http://dev7studios.com"><img src="img/slide2.jpg" alt="" title="#htmlcaption" /></a>
        // <img src="img/slide3.jpg" alt="" title="This is an example of a caption" />
        // <img src="img/slide4.jpg" alt="" />
// <div id="htmlcaption" class="nivo-html-caption">
//     <strong>This</strong> is an example of a <em>HTML</em> caption with <a href="#">a link</a>.
// </div>
echo '    </div>
</div>

<script type="text/javascript">
$(window).load(function() {
    $("#slider").nivoSlider({
    	effect: "random",
    	directionNav: false,
    	controlNav: true
    });
});
</script>


    </div>';
        }
    ?>
@endif

    @foreach ($posts as $post)
        <div class="post">
<a href='{{ URL::to_action('post@show', array($post->id)) }}'>
            <div 
                <?php
                if ( $post->img )
                {
                    echo "class='postheader' style='background: url(" . AuxImage::get_uri($post->img) . ")'";
                }
                ?>
 >{{ $post->title }}</div></a></div>
            <p>{{ substr($post->body,0, 120).' [..]' }}</p>
            <h5>by {{ $post->author()->first()->nickname }}</h5>
            <p>{{ HTML::link_to_action('post@show', 'Read more &rarr;', array('postid' => $post->id)) }}</p>
        </div>
    @endforeach
@endsection
