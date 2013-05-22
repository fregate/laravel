
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="{{ URL::base() }}/" >
    <title>Wordpush</title>
<!--     {{ HTML::style('css/style.css') }}
 -->
    <!-- Usually in the <head> section -->
<link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/nivo/light/light.css" type="text/css" media="screen" />
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

<script src="js/jquery.dev.js" type="text/javascript" media='screen'></script>
<script src="js/bootstrap.js"></script>

@yield('morelinks')

</head>
<body>
    <div class="header">
	    @if ( Auth::guest() )
	        {{ HTML::link('login', 'Login') }}
	        {{ HTML::link_to_action('account@signup', 'Register') }}
	        {{ HTML::link('/', 'Home') }}
	        <script src="//ulogin.ru/js/ulogin.js"></script>
<div id="uLogin" data-ulogin="display=small;fields=first_name,last_name;providers=facebook,vkontakte,twitter,google;hidden=odnoklassniki,mailru,yandex,openid;redirect_uri={{ rawurlencode(URL::base() . '/social') }}"></div>
	    @else
	        {{ HTML::link('logout', 'Logout') }}
	        {{ HTML::link_to_route('post', 'New post', array('new')) }}
	        {{ HTML::link('/', 'Home') }}
	        <br>
	        {{ HTML::link(URL::to_action('account@show', array('uid' => Auth::user()->id)), 'View '.Auth::user()->nickname.' profile' ) }}
        @yield('moderation')
	    @endif
	    <hr />
    </div>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
