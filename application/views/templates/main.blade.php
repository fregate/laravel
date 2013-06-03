
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
	<link rel="stylesheet" href="css/s.css" />
	<link href='http://fonts.googleapis.com/css?family=PT+Sans|PT+Sans+Narrow|Open+Sans+Condensed:700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

@yield('morelinks')

</head>
<body>
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
<!-- 		<div id="commonnav" class="commonmenu">
			<ul>
				<li><a href="">Лента новостей</a></li>
				<li><a href="">Энциклопедия</a></li>
				<li><a href="">Архивы</a></li>
				<li><a href="">О клубе</a></li>
			</ul>
		</div> -->
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

	<div class='grid_8 prefix_2 suffix_2'>
		<div class='footer'>
			<div>
			Клуб Квант <a target=_new href="http://www.phys.nsu.ru/main/">физического факультета</a><br><a href="http://www.nsu.ru" target=_new>Новосибирского Государственного Университета</a>
			</div>
			<div class="social_accounts">
				Мы в социальных сетях<br>
				<a href="https://twitter.com/clubquant" class="tw" title="Twitter" target=_new></a>
				<a href="https://www.facebook.com/clubquant" class="fb" title="Facebook" target=_new></a>
				<a href="http://vk.com/clubquant" class="vk" title="ВКонтакте" target=_new></a>
			</div>
		</div>
	</div>
</div>
</body>
</html>
