@layout('templates.main')

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
    <div class="postcaption">Aministration</div></div>
@endsection

@section('content')
	@if ( !Auth::guest() && User::find(Auth::user()->id)->has_role('admin') )
<div class='userprofilemenu'>
<ul class="usernav">
  <li id="managepins"><a href="{{URL::to_action('admin@pins')}}"><p>Manage Pins</p></a></li>
  <li id="manageusers"><a href="{{URL::to_action('admin@users')}}"><p>Manage Users</p></a></li>
</ul>
</div>
		@yield('manage')
	@else
		<div class='msgerror'>
			You don't have permissions to view this part of the site
		</div>
	@endif
@endsection

@section('moderation')
	{{ HTML::link_to_action('admin@index', 'Admin panel') }}

@endsection
