@layout('templates.main')

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
    <div class="postcaption">Aministration</div></div>
@endsection

@section('content')
<br>
	@if ( !Auth::guest() && User::find(Auth::user()->id)->has_role('admin') )
		{{ HTML::link_to_action('admin@pins', 'Manage Pins') }} 
		{{ HTML::link_to_action('admin@users', 'Manage Users') }}
		@yield('manage')
	@else
		<div class='msgerror'>
			You don't have permissions to view this part of the site
		</div>
	@endif
@endsection

@section('moderation')
	{{ HTML::link_to_action('admin@index', 'Admin panel') }}

	<!--
	// determine original size of picture
	<script>
var H = $("#img").height(),
	W = $("#img").width();

$('body').append('<br><br>Height found on DOM element:   '+W+' x '+H);

var img = new Image();
img.src = $('#img').attr('src');

img.onload = function() {
	var H2 = this.height,
    	W2 = this.width;
    $('body').append('<br><br>Height found on JS element:   '+W2+' x '+H2);
}
</script>

<xxxhtml>
	<img src="http://2013.kvant.me/title.png" height="100"px width="120px" id="img" />
</xxxhtml>
	 -->

@endsection
