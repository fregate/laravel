@layout('templates.main')

@section('content')
@if (!Session::has('everything_ok'))
    {{ Form::open('remindpass') }}

        @if (Session::has('input_errors'))
            <p class="error">Empty email</p>
        @endif

        <p>{{ Form::label('email', 'Enter email') }}</p>
        <p>{{ Form::email('email', Input::old('email')) }}</p>

        @if (Session::has('captcha_errors'))
            <p class="error">Mistyped captcha</p>
        @endif

<?php
echo Recaptcha\Recaptcha::recaptcha_get_html('6LePcOASAAAAAMRzVZ5ZoE-iNXpfRmRlwxdosLdG');
?>

        <p>{{ Form::submit('Send password') }}</p>
    {{ Form::close() }}

    @if (Session::has('email_errors'))
        <p class="error">No user with this email</p>
        <p>{{HTML::link_to_route('signup', 'Sign up!')}}</p>
    @endif

@else
    <p>Please check your email for information</p>
    {{ HTML::link('/', 'Return to main page') }}
@endif

@endsection
