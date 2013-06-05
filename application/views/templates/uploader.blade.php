
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

	<link rel="stylesheet" href="css/s.css" />
	<link href='http://fonts.googleapis.com/css?family=PT+Sans|PT+Sans+Narrow|Open+Sans+Condensed:700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

@yield('morelinks')

</head>
<body>

<div class="navbar">
 <div class="navbar-inner">
  <span class="brand"><h3>Upload image interface</h3></span>
    <div class="well well-small pull-right" >
    <a class="btn btn-inverse" href="{{URL::to('/')}}">
      <i class="icon-home icon-white"></i>
      Return to site
    </a>
  </div>
</div>
</div>

@yield('content')

<br>
<div class="navbar">
 <div class="navbar-inner">
  <div class="btn-group" >
    <a class="btn btn-inverse" href="{{URL::to('/')}}">
      <i class="icon-home icon-white"></i>
      Return to site
    </a>
  </div>
  <ul class="nav pull-right">
    <li class="active">
      <a >You logged as {{Auth::user()->nickname}}</a>
    </li>
  </ul>
 </div>
</div>


</body>
</html>
