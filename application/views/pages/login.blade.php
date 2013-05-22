@layout('templates.main')
@section('content')
    {{ Form::open('login') }}
        <!-- check for login errors flash var -->
        @if (Session::has('login_errors'))
            <span class="error">Username or password incorrect.</span>
        @endif
        <!-- username field -->
        <p>{{ Form::label('email', 'E-mail') }}</p>
        <p>{{ Form::email('email', Input::old('email')) }}</p>
        <!-- password field -->
        <p>{{ Form::label('password', 'Password') }}</p>
        <p>{{ Form::password('password') }}</p>
        @if (Session::has('captcha_error'))
            <span class="error">Mistyped captcha.</span>
        @endif
<?php
echo Recaptcha\Recaptcha::recaptcha_get_html('6LePcOASAAAAAMRzVZ5ZoE-iNXpfRmRlwxdosLdG');
?>
        <!-- submit button -->
        <p>{{ Form::submit('Login') }}</p>
    {{ Form::close() }}
@endsection
