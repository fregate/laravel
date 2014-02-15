@layout('templates.main')

@section('morelinks')
<meta property="og:type" content="article" /> 
<meta property="og:url" content="{{URL::full()}}" /> 
<meta property="og:title" content="Энциклопедия Клуба Квант" /> 
<meta property="og:image" content="URL::base().'/img/cqlogotop.png'}}" />

<script type="text/javascript" src="js/isotope.min.js"></script>
<link rel="stylesheet" href="css/iso.s.css" />
<style type="text/css">
    .previewarticle {

    }
</style>
@endsection

@section('pinned')
<?php
$pins = Pin::where('showtime_start', '<', date('c'))
         ->where('showtime_end', '>', date('c'))->get();

if(count($pins) != 0) {
    $b = IoC::resolve('bungs');

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
            echo '<img src="' . $b->get_bung_img() . '" title="#' . $linkuq . '"/>';

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
    $b = IoC::resolve('bungs');

    echo '<div class="imagelayer"><img src="' . $b->get_bung_img() . '"></div>';
    echo '<div class="masklayer" style="top: -215px;"><img src="img/m2.png"></div>';
}
?>
@endsection

@section('content')

<div class="isotope js-isotope is-varying-sizes wikipattern" data-isotope-options='{ "masonry": { "columnWidth": 203 } }'>

<div class="element-item width2 height2"><h3>Энциклопедия Клуба Квант</h3>
За время, пока КК существует, накопилось так много информации, что мы уже выпустили 2 тома бумажной энциклопедии. И теперь она может быть не только у вас на полке, но и в электронном виде - всегда самые свежие фотографии и точная информация.</div>
<div class="element-item h13">{{ HTML::link_to_action('wiki.new', 'Написать новую статью!') }}</div>
<div class="element-item h13">Побродить по категориям</div>
<div class="element-item h13 empty"></div>

<?php

// create avaiable letters to browse
// $letters = DB::table('wiki_articles')->group_by('fl')->only('fl');
    // foreach ($letters as $ltt) {
    // }

$articles_ids = DB::table('wiki_articles')->get('id');
$perpage = min(9, count($articles_ids));

if(count($articles_ids)) {
    $randids = array_rand($articles_ids, $perpage);
    if(count($articles_ids) == 1)
       $randids = array($randids);

    shuffle($randids);

    array_walk($randids, function(&$id, $key, $arr) {
        $wa = WikiArticle::find($arr[$id]->id);
        echo '<div class="element-item h13"><a class="headerarticle" href="' . URL::to_action('wiki', array('id' => $wa->uri)) 
        . '">' . $wa->content()->title . '</a><div class="previewarticle"><small>' . strip_tags(substr($wa->content()->body, 0, 126)) // 3 * 42
        . '</small></div></div>';
    }, $articles_ids);

}
?>

</div>

@endsection
