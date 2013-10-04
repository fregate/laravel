@layout('templates.static_t')

@section('metas')
@if ( isset($sp) )
    {{ $sp->meta }}
@else
    {{ $metas }}
@endif
@endsection

@section('title')
@if ( isset($sp) )
{{ $sp->title }}
@else
{{ $title }}
@endif
@endsection

@section('scripts')
@if ( isset($sp) )
    {{ $sp->scripts }}
@else
    {{ $scripts }}
@endif
@endsection

@section('styles')
@if ( isset($sp) )
    {{ $sp->styles }}
@else
    {{ $styles }}
@endif
@endsection

@section('content')
@if ( isset($sp) )
    {{ $sp->content }}
@else
    {{ $content }}
@endif
@endsection
