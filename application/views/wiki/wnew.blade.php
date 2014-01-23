@layout('templates.main')

@section('morelinks')
<meta property="og:type" content="article" /> 
<meta property="og:url" content="{{URL::full()}}" /> 
<meta property="og:title" content="Энциклопедия Клуба Квант" /> 
<meta property="og:image" content="URL::base().'/img/cqlogotop.png'}}" />

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<script type="text/javascript" src="js/isotope.min.js"></script>
<link rel="stylesheet" href="css/iso.s.css" />

<link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
<link rel="stylesheet" type="text/css" href="css/markitup/style.css">
<script type="text/javascript" src="js/jquery.markitup.min.js"></script>
<script type="text/javascript" src="js/editor.js"></script>
<script src="js/jquery-ui.min.js"></script>

<script type="text/javascript">
$(function() {
  $( "#inserturl" ).dialog({
    modal: true,
    autoOpen: false,
    closeOnEscape: true,
    draggable:true,

    buttons: {
      "Insert Link": function() {
        $( this ).dialog( "close" );
        $.markItUp( { target: 'textarea', replaceWith: '<a href="' + $('#linkurl').val() + '">' + $('#linkpromt').val() + '</a>' } );
        $('#linkurl').val("http://");
      },
      "Cancel": function() {
        $( this ).dialog( "close" );
      }
    }
  });
});

$(function() {
  $( "#insertmedia" ).dialog({
    modal: true,
    autoOpen: false,
    closeOnEscape: true,
    draggable:true,

    buttons: {
      "Insert Link": function() {
        $( this ).dialog( "close" );
        $.markItUp( { target: 'textarea', replaceWith: '<media src="' + $('#mediaurl').val() + '">' } );
        $('#linkurl').val("http://");
      },
      "Cancel": function() {
        $( this ).dialog( "close" );
      }
    }
  });
});

$(function() {
  $( "#insertimg" ).dialog({
    modal: true,
    autoOpen: false,
    closeOnEscape: true,
    draggable:true,

    buttons: {
      "Insert Link": function() {
        $( this ).dialog( "close" );
        $.markItUp( { target: 'textarea', replaceWith: '<img src="' + $('#imgurl').val() + '" alt="' + $('#imgalt').val() + '">' } );
        $('#linkurl').val("http://");
      },
      "Cancel": function() {
        $( this ).dialog( "close" );
      }
    }
  });
});

function img_prompt(h) {
  $('#insertimg').dialog('open');
}
function url_prompt(h) {
  $('#inserturl').dialog('open');
}
function media_prompt(h) {
  $('#insertmedia').dialog('open');
}

var es = {
  onTab:      {keepDefault:false, replaceWith:'    '},
  markupSet:  [
    {name:'Bold', className: 'Bold', key:'B', openWith:'(!(<b>|!|<strong>)!)', closeWith:'(!(</b>|!|</strong>)!)' },
    {name:'Emphasis', className: 'Emphasis', key:'I', openWith:'(!(<i>|!|<em>)!)', closeWith:'(!(</i>|!|</em>)!)'  },
    {name:'Underline', className: 'Underline', key:'U', openWith:'<u>', closeWith:'</u>' },
    {name:'Superscript', className: 'Sup', openWith:'<sup>', closeWith:'</sup>' },
    {name:'Subscript', className: 'Sub', openWith:'<sub>', closeWith:'</sub>' },
    {separator:'---------------' },
    {name:'Spoiler', className: 'Spoiler', openWith:'<spoiler>', closeWith:'</spoiler>' },
    {name:'Irony', className: 'Irony', openWith:'<irony>', closeWith:'</irony>' },
    {separator:'---------------' },
    {name:'Insert Picture', className: 'Image', dropMenu: [
      {name:'Insert link', className: 'Image', key:'P', beforeInsert: img_prompt } // ,
      // {name:'Insert from gallery', className: 'Image', key:'G', beforeInsert: glr_prompt }
    ] },
    {name:'Link', className: 'Link', key:'L',  beforeInsert: url_prompt },
    {name:'Video', className: 'Video', key: 'M',  beforeInsert: media_prompt }
  ]
};

$(document).ready(function() {
  $('textarea').markItUp(es);
  $(".alert").hide();
});
</script>

@endsection

@section('pinned')
<?php
    $b = IoC::resolve('bungs');

    echo '<div class="imagelayer"><img src="' . $b->get_bung_img() . '"></div>';
    echo '<div class="masklayer" style="top: -215px;"><img src="img/m2.png">';
    echo '<div class="postcaption" id="posttitle">Новая статья</div></div>';
?>
@endsection

@section('content')
<br>
    {{ Form::open('wiki/new', 'POST', array('id' => 'addWikiForm')) }}
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

        <!-- submit button -->
        <button type="submit" class="btn btn-success">
            <i class="icon-ok icon-white"></i>
            <span>Create article</span>
        </button>
    {{ Form::close() }}

<div class='alert'></div>

<div id="insertimg" title="Insert image" >
   <p><label for="imgurl">Enter image URL</label></p>
   <p><input type=text id="imgurl" value="http://"/></p>
   <p><label for="imgalt">Enter image description</label></p>
   <p><input type=text id="imgalt"/></p>
</div>

<div id="inserturl" title="Insert Link Url" >
   <p><label for="linkurl">Enter URL</label></p>
   <p><input type=text id="linkurl" value="http://"/></p>
   <p><label for="linkpromt">Enter description</label></p>
   <p><input type=text id="linkpromt"/></p>
</div>

<div id="insertmedia" title="Insert online media (youtube, vimeo)" >
   <p><label for="mediaurl">Enter video URL</label></p>
   <p><input type=text id="mediaurl" value="http://"/></p>
</div>

@endsection
