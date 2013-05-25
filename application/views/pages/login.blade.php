@layout('templates.main')

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png"></div>
@endsection

@section('content')
<br>
    {{ Form::open('login') }}
        <!-- check for login errors flash var -->
        @if (Session::has('login_errors'))
            <span class="error">Username or password incorrect.</span>
        @endif
        <!-- username field -->
        <p>{{ Form::label('email', 'E-mail') }} {{ Form::email('email', Input::old('email')) }}</p>
        <!-- password field -->
        <p>{{ Form::label('password', 'Password') }} {{ Form::password('password') }}</p>

        <p>{{ Form::label('rememberme', 'Remember me') }} {{ Form::checkbox('rememberme', '1', Input::old('rememberme')) }}</p>

        @if (Session::has('captcha_error'))
            <span class="error">Mistyped captcha.</span>
        @endif
<?php
echo Recaptcha\Recaptcha::recaptcha_get_html('6LePcOASAAAAAMRzVZ5ZoE-iNXpfRmRlwxdosLdG');
?>
        <!-- submit button -->
        <p>{{ Form::submit('Login') }}</p>
    {{ Form::close() }}

{{ HTML::link_to_action("account@remindpass", "Forget password") }}
@endsection
