@layout('templates.main')

@section('morelinks')
    <link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
    <link rel="stylesheet" type="text/css" href="css/markitup/style.css">
    <script type="text/javascript" src="js/jquery.markitup.min.js"></script>

    <script type="text/javascript">
    var post_editor_settings = {
        onTab:          {keepDefault:false, replaceWith:'    '},
        markupSet:  [   
            {name:'Bold', className: 'Bold', key:'B', openWith:'(!([b]|!|[strong])!)', closeWith:'(!([/b]|!|[/strong])!)' },
            {name:'Emphasis', className: 'Emphasis', key:'I', openWith:'(!([i]|!|[em])!)', closeWith:'(!([/i]|!|[/em])!)'  },
    //      {name:'Stroke through', className: 'Stroke', key:'S', openWith:'<del>', closeWith:'</del>' },
            {name:'Underline', className: 'Underline', key:'U', openWith:'[u]', closeWith:'[/u]' },
            {name:'Superscript', className: 'Sup', openWith:'[sup]', closeWith:'[/sup]' },
            {name:'Subscript', className: 'Sub', openWith:'[sub]', closeWith:'[/sub]' },
            {separator:'---------------' },
            {name:'Spoiler', className: 'Spoiler', openWith:'[spoiler]', closeWith:'[/spoiler]' },
            {separator:'---------------' },
            {name:'Picture', className: 'Image', key:'P', replaceWith:'[img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" /]' },
            {name:'Link', className: 'Link', key:'L', openWith:'[a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)]', closeWith:'[/a]', placeHolder:'Your text to link...' },
            {name:'Video', className: 'Video', replaceWith:'[video src="[![Youtube Link:!:http://]!]" /]' }
    //      {name:'Audio', className: 'Audio', openWith:'<audio>', closeWith:'</audio>' }
        ]
    };

    $(document).ready(function(){
        $('textarea').markItUp(post_editor_settings);
    });
    </script>
@endsection

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png"></div>
@endsection

@section('content')
<br>
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
