
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<base href="{{ URL::base() }}/" >

    <title>Сайт Клуба Квант</title>

	<script src="js/jquery.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<script type="text/javascript" src="js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="css/960_12_col.css" />
	<link href='http://fonts.googleapis.com/css?family=PT+Sans|PT+Sans+Narrow|Open+Sans|Open+Sans+Condensed:700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/s.css" />

@yield('morelinks')

</head>
<body>
<div id="contentwrap"><div id="mainwrap">
<div class="container_12">
	<div class='grid_2 topheader'><a href='{{ URL::to("/") }}' title="На главную страницу"> <div class='cqlogotop' ></div> </a></div>
	<div class='grid_8 topheader'><div class='pinnednews'>
		@yield('pinned')
	</div></div>

	<div class='grid_2 topheader'><div class="menuwrap">
		<div class='usermenu'>
	    @if ( Auth::guest() )
	        {{ HTML::link('login', 'Войти') }} |
	        {{ HTML::link_to_action('account@signup', 'Регистрация') }}
	        <script src="//ulogin.ru/js/ulogin.js"></script>
<div id="uLogin" data-ulogin="display=small;fields=first_name,last_name;providers=facebook,vkontakte,twitter,google;hidden=odnoklassniki,mailru,yandex,openid;redirect_uri={{ rawurlencode(URL::base() . '/social') }}"></div>
	    @else
	        {{ HTML::link_to_action('account@show', 'View '.Auth::user()->nickname.' profile', array('uid' => Auth::user()->id)) }} <br>
	        {{ HTML::link_to_route('post', 'New post', array('new')) }} <br>
	        {{ HTML::link('logout', 'Logout') }} <br>
        @yield('moderation')
	    @endif
		</div>
 		<div id="commonnav" class="commonmenu">
			<ul>
				<li>{{ HTML::link('', 'Лента новостей') }}</li>
				<!--<li>{{ HTML::link('wiki', 'Энциклопедия') }}</li>
				 <li><a href="">Архивы</a></li> -->
				<li>{{ HTML::link('about', 'О клубе') }}</li>
			</ul>
		</div>
	</div></div>
        <div class="clear"></div>

	<div class='grid_8 prefix_2 suffix_2'>
		<div class='content'>
			<div class='decoration1'></div>
			<div class='decoration2'></div>
			@yield('content')
		</div>
	</div>
	<div class="clear"></div>
</div>
</div></div>

<div id="footerwrap">
<div class="container_12" >
	<div class='grid_8 suffix_2 prefix_2'><div class="footer"><div class="footcontent">
		<div class="pull-left" style="display:inline">
			Клуб Квант <a target=_new href="http://www.phys.nsu.ru/">Физического факультета</a><br><a href="http://www.nsu.ru" target=_new>Новосибирского Государственного Университета</a>
		</div>
		<div class="social_accounts pull-right" style="display:inline">
			Мы в социальных сетях<br>
			<a href="https://twitter.com/clubquant" class="tw" title="Twitter" target=_new></a>
			<a href="https://www.facebook.com/clubquant" class="fb" title="Facebook" target=_new></a>
			<a href="http://vk.com/clubquant" class="vk" title="ВКонтакте" target=_new></a>
			<a href="http://www.youtube.com/clubquant" class="yt" title="YouTube" target=_new></a>
		</div>
	</div></div></div>
</div>
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
