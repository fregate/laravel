@layout('templates.main')

@section('morelinks')
<link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
<link rel="stylesheet" type="text/css" href="css/markitup/style.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<link rel="stylesheet" href="css/jquery.jcrop.min.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />

<script type="text/javascript" src="js/jquery.markitup.min.js"></script>
<script type="text/javascript" src="js/editor.js"></script>

<script src="bundles/jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="bundles/jupload/js/jquery.iframe-transport.js"></script>
<script src="bundles/jupload/js/jquery.fileupload.js"></script>
<script src="js/jquery.jcrop.min.js"></script>
<script src="js/jquery-ui.min.js"></script>

<script type="text/javascript">
var nselectedimage = -1;

function init_jcrop(bpreview) {
  if(nselectedimage < 0) {
    $("#aop").dialog("close");
    return;
  }
  resize_dlg("#aop");
  var idi = nselectedimage;

  $('#imagecontainer').html('<img src="{{URL::to_route("image")}}/'+idi+'" id="target"/>');
  $('#preview-pane .preview-container img').attr('src', "{{URL::to_route('image')}}/"+idi);

  $preview = $('#preview-pane');
  $pcnt = $('#preview-pane .preview-container');
  $pimg = $('#preview-pane .preview-container img');

  if(bpreview) {
    $("#preview-pane").show().draggable();
  }

  var img = new Image();
  img.src = $pimg.attr('src');

  img.onload = function() {
    H = this.height,
    W = this.width;
    if(jcrop_api != undefined) {
      jcrop_api.setSelect([0,0,W,H]);
    }
  }

  var jcoptions;
  if(bpreview) {
    jcoptions = {
      onChange: updatePreview,
      onSelect: updatePreview,
      onRelease: clearCoords,
      aspectRatio: $pcnt.width() / $pcnt.height(),
      setSelect: [0, 0, W, H]
    }
  }
  else {
    jcoptions = {
      onChange: updatePreview,
      onSelect: updatePreview,
      onRelease: clearCoords,
      setSelect: [0, 0, W / 2, H / 2]
    }
  }

  $('#target').Jcrop(jcoptions, function() {
    // Use the API to get the real image size
    var bounds = this.getBounds();
    boundx = bounds[0];
    boundy = bounds[1];
    // Store the API in the jcrop_api variable
    jcrop_api = this;
  });
}

function init_file_upload(selid) {
  $(selid).fileupload({
    url: "{{URL::base()}}/upload/xxx",
    dataType: 'json',
    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
    minFileSize: 1,
    done: function (e, data) {
//      console.log(data.result);
      get_gallery_ajax(true);
      $("#upload_waiting").hide();
      $(".fileinput-button").removeAttr('disabled');
    },
    progressall: function (e, data) {
      $("#upload_waiting").show();
      $(".fileinput-button").attr('disabled', 'disabled');
    }
  });
};

var mconst = 40;
function resize_dlg(dlgid) {
  if( $( dlgid ).dialog( "isOpen")) {
    $( dlgid ).dialog( "option", "height", window.innerHeight - mconst );
    $( dlgid ).dialog( "option", "width", window.innerWidth - 2 * mconst );
  }
}

$(window).resize(function(){
  resize_dlg("#dlgheaderimg");
  resize_dlg("#aop");
});

$(function () {
  $( "#aop" ).dialog({
    autoOpen: false,
    dialogClass: "dlgpos",
    resizable:false,
    modal:true,
    draggable:false,
    width:500,
    height:500,
    position: { my: "center top", at: "center top", of: window },
    beforeClose: function() {
      $("#preview-pane").hide();
    }
  });
});

$(function() {
  $( "#dlgheaderimg" ).dialog({
    open: function() {
      get_gallery_ajax(false);
      resize_dlg("#dlgheaderimg");
      $(".ui-dialog-buttonpane").prepend('<span class="btn btn-success fileinput-button"><i class="icon-plus icon-white"></i><span>Add images...</span> <input id="fileupload" type="file" name="files[]" multiple><span id="upload_waiting"><img src="img/loading.gif"></span></span>');
      init_file_upload("#fileupload");
    },
    beforeClose: function() {
      $('span.fileinput-button').remove();
    },
    autoOpen: false,
    dialogClass: "dlgpos",
    resizable:false,
    modal:true,
    draggable:false,
    width:500,
    height:500,
    position: { my: "center top", at: "center top", of: window },
  });
});

function glr_handler(trg) {
  xxx = [{ 
    text: "Edit image",
    click: function() { 
      if(nselectedimage < 0)
        return;

      $( this ).dialog( "close" );

      $("#aop").dialog({ buttons: {
          "Insert image": function() {
            $( this ).dialog( "close" );
            $.markItUp( { target: trg, replaceWith: '<img src="{{URL::to_route("image")}}/' + nselectedimage + '/' + $("#prepimglink").val() + '" alt="Place description here...">' } );
          },
          "Cancel": function() {
            nselectedimage = -1;
            $( this ).dialog( "close" );
          }
      } } );

      $('#aop').dialog( { 
        open : function(event, ui) {
          init_jcrop(false);
        } 
      });

      $('#aop').dialog('open');
    }
  },
  {
    text: "Cancel",
    click: function() {
      nselectedimage = -1;
      $( this ).dialog( "close" );
    }
  }];
  $( "#dlgheaderimg" ).dialog( "option", "buttons", xxx );
  $( "#dlgheaderimg" ).dialog( "open" );
}

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

function glr_prompt(h) {
  glr_handler('#bodyArea');
}

var es = {
  onTab:      {keepDefault:false, replaceWith:'    '},
  markupSet:  [
    {name:'Heading 4', key:'4', openWith:'<h4(!( class="[![Class]!]")!)>', closeWith:'</h4>', placeHolder:'Your title here...' },
    {name:'Heading 5', key:'5', openWith:'<h5(!( class="[![Class]!]")!)>', closeWith:'</h5>', placeHolder:'Your title here...' },
    {name:'Heading 6', key:'6', openWith:'<h6(!( class="[![Class]!]")!)>', closeWith:'</h6>', placeHolder:'Your title here...' },
    {separator:'---------------' },
    {name:'Bold', className: 'Bold', key:'B', openWith:'(!(<b>|!|<strong>)!)', closeWith:'(!(</b>|!|</strong>)!)' },
    {name:'Emphasis', className: 'Emphasis', key:'I', openWith:'(!(<i>|!|<em>)!)', closeWith:'(!(</i>|!|</em>)!)'  },
    {name:'Underline', className: 'Underline', key:'U', openWith:'<u>', closeWith:'</u>' },
    {name:'Superscript', className: 'Sup', openWith:'<sup>', closeWith:'</sup>' },
    {name:'Subscript', className: 'Sub', openWith:'<sub>', closeWith:'</sub>' },
    {separator:'---------------' },
    {name:'Ul', className: 'Symbol List', openWith:'<ul>\n', closeWith:'</ul>\n' },
    {name:'Ol', className: 'Numeric List', openWith:'<ol>\n', closeWith:'</ol>\n' },
    {name:'Li', className: 'List Item', openWith:'<li>', closeWith:'</li>', placeHolder:'Your item here...' },
    {separator:'---------------' },
    {name:'Spoiler', className: 'Spoiler', openWith:'<spoiler>', closeWith:'</spoiler>' },
    {name:'Irony', className: 'Irony', openWith:'<irony>', closeWith:'</irony>' },
    {separator:'---------------' },
    {name:'Insert Picture', className: 'Image', dropMenu: [
      {name:'Insert link', className: 'Image', key:'P', beforeInsert: img_prompt } ,
      {name:'Insert from gallery', className: 'Image', key:'G', beforeInsert: glr_prompt }
    ] },
    {name:'Link', className: 'Link', key:'L',  beforeInsert: url_prompt },
    {name:'Video', className: 'Video', key: 'M',  beforeInsert: media_prompt }
  ]
};

$(document).ready(function() {
  $('textarea').markItUp(es);
  $(".alert").hide();
  $("#headerimagecontainer").hide();

  $('#addWikiForm').submit(function(e) {
    var rrr = html_parse($('textarea[name="body"]'));
    if(rrr === false) {
      $(".alert").addClass('alert-error').text('Error in html. Please be careful').show();
      return false;
    }

    var x = $('input[name="title"]');
    x.val(x.val().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'));
  });
});

function get_gallery_ajax(bforce) {
  if(!bforce && $("#gallerycontainer > span").length != 0)
    return;

  $.get("{{URL::to_route('pix')}}/200", function(msg) {
    $("#gallerycontainer").html(msg);
    spns = $("#gallerycontainer > span");
    spns.each(function(i) {
      $(this).on('dblclick', function() {
        nselectedimage = $(this).data('idi');
        xzabuttons = $("#dlgheaderimg").dialog("option", "buttons");
        xzabuttons[0].click.apply($("#dlgheaderimg"));
      }).on('click', function() {
        $('#gallerycontainer > span[active]').removeAttr('active');
        $(this).attr('active','active');
        nselectedimage = $(this).data('idi');
      });
    });
  });
}

function update_header_preview()
{
  if($('input[name="imageparam"]').val() == "" || $('input[name="imageparam"]').val() == undefined || $('input[name="imageid"]').val() < 0 || $('input[name="imageid"]').val() == undefined)
  {
    $("#headerimagecontainer").hide();
    $("#headerimagepreview > img").attr('src', '').attr('data-idi', -1);
    return;
  }

  $("#headerimagecontainer").show();
  $("#headerimagepreview > img").attr('src', '{{URL::base()}}/image/'+$('input[name="imageid"]').val()+"/"+$('input[name="imageparam"]').val());
}

</script>

<style type="text/css">
#preview-pane {
  display:none;
  z-index: 2000;
  padding: 6px;
  background-color: white;
  width: auto;
  position: fixed;
  top: 20px;
  left: 20px;
  -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, 1);
  -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, 1);
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 1);
  cursor: move;
}

</style>

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
        <p>{{ Form::textarea('body', Input::old('body'), array('id' => 'bodyArea')) }}</p>

        <p>
        Select article category 
        <div class="btn-group">
        <a class="btn dropdown-toggle btn-info" data-toggle="dropdown" href="#">Category <span class="caret"></span></a>
        <ul class="dropdown-menu"><li><a tabindex="-1" href="#">Cat1</a><li></ul>
        </div>
        <input type="hidden" name="category" value="-1">
        </p>

        <!-- submit button -->
        <div>
        <button class="btn btn-warning pull-right">
            <i class="icon-eye-open icon-white"></i>
            <span>Посмотреть</span>
        </button>
        <button type="submit" class="btn btn-success pull-left">
            <i class="icon-ok icon-white"></i>
            <span>Создать статью</span>
        </button>
        </div>
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

<div id="dlgheaderimg" title="Select image for header">
    <div id="gallerycontainer"></div>
</div>

<div id="aop" title="Select image area of interest" >
    <div id="imagecontainer"></div>
    <input type=text readonly id="prepimglink" value="">
</div>

<div id="preview-pane" class="ui-widget-content">
    <div class="preview-container">
      <img src="" class="jcrop-preview" alt="Preview" />
    </div>
</div>

@endsection
