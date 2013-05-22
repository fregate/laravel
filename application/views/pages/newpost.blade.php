@layout('templates.main')
@section('content')
    {{ Form::open_for_files('post/new') }}
	    <!-- author -->
	    {{ Form::hidden('author_id', $user->id) }}
	    <!-- title field -->
        <p>{{ Form::label('title', 'Title') }}</p>
        {{ $errors->first('title', '<p class="error">:message</p>') }}
        <p>{{ Form::text('title', Input::old('title')) }}</p>
        <!-- body field -->
        <p>{{ Form::label('body', 'Body') }}</p>
        {{ $errors->first('body', '<p class="error">:message</p>') }}
        <p>{{ Form::textarea('body', Input::old('body')) }}</p>
        <!-- title image -->
        {{ $errors->first('uimage', '<p class="error">:message</p>') }}
        <p>{{ Form::file('uimage') }}</p>
        <!-- submit button -->
        <p>{{ Form::submit('Create') }}</p>
    {{ Form::close() }}
@endsection
