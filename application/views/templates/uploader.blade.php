
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
    <div id="ajaxmodal" class="modal" style="display:block">
        <div class="modal-header">
            <h3>Upload image</h3>
        </div>
        <div class="modal-body">
			@yield('content')
        </div>
        <div class="modal-footer">
        	{{HTML::link('/', 'Go home')}}
        </div>
    </div>
</body>
</html>
