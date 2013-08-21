@layout('templates.main')

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
    <div class="postcaption">Aministration</div></div>
@endsection

@section('content')
<?php
    $thisuser = !Auth::guest() && Auth::user()->id == $user->id;
?>
<div class='userprofilemenu'>
<ul class="usernav">
  <li id="ligeneral"><a href="{{ URL::to_action('account@show', array('uid' => $user->id)) }}"><p>General info</p></a></li>
  <li id="liposts"><a href="{{ URL::to_action('account@post', array('uid' => $user->id)) }}"><p>Posts</p></a></li>
  <li id="licomms"><a href="{{ URL::to_action('account@comment', array('uid' => $user->id)) }}"><p>Comments</p></a></li>
  <li id="liimgs"><a href="{{ URL::to_action('account@gallery', array('uid' => $user->id)) }}"><p>Gallery</p></a></li>
@if($thisuser)
  <li id="lifav"><a href="{{ URL::to_action('account@favorite', array('uid' => $user->id)) }}"><p>Избранное</p></a></li>
@endif
</ul>
</div>

		@yield('profilesection')

@endsection

@section('moderation')
<?php
if(!Auth::guest() && Auth::user()->has_role('admin'))
{
    echo HTML::link_to_action('admin@index', 'Admin panel');
}
?>
@endsection
