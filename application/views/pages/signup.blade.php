@layout('templates.main')

@section('pinned')
<?php
    $b = IoC::resolve('bungs');
    echo '<div class="imagelayer"><img src="' . $b->get_bung_img() . '"></div>';
?>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png"></div>
@endsection

@section('content')
<br>
    {{ Form::open('signup') }}
        <!-- check for login errors flash var -->
        @if (Session::has('input_errors'))
            <span class="error">Wrong fields</span>
        @endif

        <p>{{ Form::label('firstname', 'First name') }}</p>
        <p>{{ Form::text('firstname', Input::old('firstname')) }}</p>

        <p>{{ Form::label('lastname', 'Last name') }}</p>
        <p>{{ Form::text('lastname', Input::old('lastname')) }}</p>

        <!-- username field 
        <p>{{ Form::label('username', 'Username') }}</p>
        <p>{{ Form::text('username', Input::old('username')) }}</p> -->
        <!-- password field -->
        @if(Input::old('social', 0) != 0)
        <p>
            Если вы хотите часто общаться на этом сайте, рекомендуем зарегистрироваться,
            набрав ваш е-мейл и пароль, при этом, вы сможете добавить еще различных акканутов,
            через какие можно вас будет идентифицировать. Либо ничего не вводить, но мы вас все равно
            запомним и будем стараться подставлять ваши данные, когда нужно и куда нужно.
        </p>
        @endif
        
        <p>{{ Form::label('email', 'Email') }}</p>
        <p>{{ Form::email('email', Input::old('email')) }}</p>

        <p>{{ Form::label('password', 'Password') }}</p>
        <p>{{ Form::password('password') }}</p>

<?php
echo Recaptcha\Recaptcha::recaptcha_get_html('6LePcOASAAAAAMRzVZ5ZoE-iNXpfRmRlwxdosLdG');
?>

        {{ Form::hidden('social', Input::old('social', 'false')) }}
        {{ Form::hidden('network', Input::old('network', 'club.quant')) }}
        {{ Form::hidden('identity', Input::old('identity')) }}
        
        <!-- submit button -->
        <p>{{ Form::submit('Sign up') }}</p>
    {{ Form::close() }}
@endsection
