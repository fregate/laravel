@layout('templates.main')

<?php
$ccc;
if($version == 0)
  $ccc = $article->content();
else
  $ccc; // get variant
?>

@section('morelinks')
<meta property="og:type" content="article" /> 
<meta property="og:url" content="{{URL::full()}}" /> 
<meta property="og:title" content="{{$ccc->title}}" /> 
<meta property="og:image" content="{{URL::base().'/img/cqlogotop.png'}}" />

  <link rel="stylesheet" href="css/sharer.css" type="text/css" />

  <script type="text/javascript" src="js/editor.js"></script>
  <script src="js/sharer.js" type="text/javascript"></script>

@endsection

@section('pinned')
<?php
    $b = IoC::resolve('bungs');
    echo '<div class="imagelayer"><img src="' . $b->get_bung_img() . '"></div>';
?>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
    <div class="postcaption" id="posttitle">Энциклопедия Клуба Квант</div>
    </div>
@endsection

@section('content')
<div class="postentry">
<h3>{{ $ccc->title }}</h3>

    <p id="articlemain" itemprop="description">{{ $ccc->body }} </p>

    <div class='posttimestamp'>
        <span class="share">
              <a class="twitter" onclick="tweet()" href="javascript: void(0)" title="Tweet this!"></a>
        </span>
        <span class="share">
              <a class="vk" onclick="vkshare()" href="javascript: void(0)" title="Опубликовать во ВКонтакте"></a>
        </span>
        <span class="share">
              <a class="fb" onClick="fbshare()" href="javascript: void(0)" title="Share on Facebook"></a>
        </span>
        <span class="share">
              <a class="gp" onclick="gpshare()" href="javascript: void(0)" title="Share on Google+"></a>
        </span>
    </div>
    <br>
</div>

@endsection
