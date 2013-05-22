@layout('templates.main')

@section('content')

@if ( !Auth::guest() && User::find(Auth::user()->id)->has_role('admin') )
{{ HTML::link_to_action('admin@pins', 'Manage Pins') }}
{{ HTML::link_to_action('admin@users', 'Manage Users') }}
@yield('manage')
@else
<div class='msgerror'>
Sorry, you don't have permissions to view this part of the site
</div>
@endif

@endsection

@section('moderation')
{{ HTML::link_to_action('admin@index', 'Admin panel') }}
@endsection
