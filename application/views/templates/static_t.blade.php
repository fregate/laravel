
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ URL::base() }}/" >

<meta property="og:type" content="article" />
<meta property="og:url" content="{{URL::full()}}" />
<meta property="og:title" content="" />

@yield('metas')

<title>
@yield('title')
</title>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/sharer.js"></script>

@yield('scripts')

<link href="css/sharer.css" rel="stylesheet" type="text/css" />
<style>
*, html, body, div { margin:0; padding:0; }

.clear {
	clear: both;
}

#slide-out {
	-moz-transition: all .3s ease-in-out;
	-webkit-transition: all .3s ease-in-out;
	-o-transition: all .3s ease-in-out;
	transition: all .3s ease-in-out;
	height: 75px;
	position: relative ;
	margin-top: -75px;
  background: #ff9f40;
}

.main-container{
	padding-top: 1px;
}

.slide-panel{
	background: #ff9f40;
	bottom:-5px;
	display:block;
	height:5px;
	padding-top:5px;
	position:absolute;
	width:100%;
	z-index:100;
	line-height: normal;
}

#slide-out:hover{
	margin-top: 0px;
}

.slide-panel:hover #slide-out{
	margin-top: 0px;
}

.homebtn {
    float: left;
    width: 50%;
}

.sharebtn {
    float: right;
    width: 50%;
    text-align: right;;
}

</style>

@yield('styles')

</head>
<body>
  <div id="slide-out">
    <div class="homebtn">
      <span class="xshare">
        <a href="{{URL::base()}}" title="Back to main site"><img src="img/statich.png"></a>
      </span>
    </div>
    <div class="sharebtn">
        <span class="xshare">
              <a class="xtwitter" onclick="tweet()" title="Tweet this!"></a>
        </span>
        <span class="xshare">
              <a class="xvk" onclick="vkshare()" title="Опубликовать во ВКонтакте"></a>
        </span>
        <span class="xshare">
              <a class="xfb" onClick="fbshare()"  title="Share on Facebook"></a>
        </span>
        <span class="xshare">
              <a class="xgp" onclick="gpshare()"  title="Share on Google+"></a>
        </span>
      </div>

      <div class="clear"></div>
      <a href="#" class="slide-panel"></a>
    </div>
  </div>

<div class="main-container" id="articlemain">
@yield('content')
</div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41235899-1', 'kvant.me');
  ga('send', 'pageview');

</script>

</body>
</html>
