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
        // Move the preview into the jcrop container for css positioning
      //      $preview.appendTo(jcrop_api.ui.holder);
//        jcrop_api.setSelect([0, 0, W / 2, H / 2]);
      });
    }

function init_file_upload(selid) {
  $(selid).fileupload({
    url: "{{URL::base()}}/upload/xxx",
    dataType: 'json',
    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
    minFileSize: 1,
    done: function (e, data) {
      console.log(data.result);
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

  $( "#btopengallery" ).click(function( event ) {
    $( "#dlgheaderimg" ).dialog( "option", "buttons", [ { 
      text: "Edit image",
      click: function() { 
        if(nselectedimage < 0)
          return;

        $( this ).dialog( "close" );

        $('input[name="imageid"]').val(nselectedimage);
        update_header_preview();

        $("#aop").dialog({ buttons: {
          "Insert image": function() {
            $( this ).dialog( "close" );
            if($("#prepimglink").val() != "")
              $('input[name="imageparam"]').val($("#prepimglink").val());

            update_header_preview();
          },
          "Cancel": function() {
            nselectedimage = -1;
            $( this ).dialog( "close" );
          }
        } } );
        $('#aop').dialog( { 
          open : function(event, ui) {
            init_jcrop(true);
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
    } ] );

    $( "#dlgheaderimg" ).dialog( "open" );
    event.preventDefault();
  });
});

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
  $( "#dlgheaderimg" ).dialog( "option", "buttons", [ { 
    text: "Edit image",
    click: function() { 
      if(nselectedimage < 0)
        return;

      $( this ).dialog( "close" );

      $("#aop").dialog({ buttons: {
        "Insert image": function() {
          $( this ).dialog( "close" );
          $.markItUp( { target: 'textarea', replaceWith: '<img src="{{URL::to_route("image")}}/' +  nselectedimage + '/' + $("#prepimglink").val() + '" alt="Place description here...">' } );
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
  } ] );

  $( "#dlgheaderimg" ).dialog( "open" );
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
      {name:'Insert link', className: 'Image', key:'P', beforeInsert: img_prompt },
      {name:'Insert from gallery', className: 'Image', key:'G', beforeInsert: glr_prompt }
    ] },
    {name:'Link', className: 'Link', key:'L',  beforeInsert: url_prompt },
    {name:'Video', className: 'Video', key: 'M',  beforeInsert: media_prompt }
  ]
};

$(document).ready(function() {
  $('textarea').markItUp(es);
});

var jcrop_api,
    boundx,
    boundy,

    // Grab some information about the preview pane
    $preview, // = $('#preview-pane'),
    $pcnt, // = $('#preview-pane .preview-container'),
    $pimg;// = $('#preview-pane .preview-container img'),

//    xsize; // = $pcnt.width(),
    //ysize; // = $pcnt.height();

var H, W;

function clearCoords(c) {
    $("#prepimglink").val('');
//        $("#prepraw").val($pimg.attr('src'));
}

function updatePreview(c)
{
  if (parseInt(c.w) > 0)
  {
        var xsize;
        var ysize;

    if($preview.is(':visible')) {
      xsize = $pcnt.width();
      ysize = $pcnt.height();
      var rx = xsize / c.w;
      var ry = ysize / c.h;

      $pimg.css({
        width: Math.round(rx * boundx) + 'px',
        height: Math.round(ry * boundy) + 'px',
        marginLeft: '-' + Math.round(rx * c.x) + 'px',
        marginTop: '-' + Math.round(ry * c.y) + 'px'
      });
    }

    $("#prepimglink").val(create_coord(c.x, c.y, c.w, c.h, W / boundx, H / boundy, xsize, ysize));
  }
}

function create_coord (x, y, w, h, aspx, aspy, Wi, Hi) {
  if(x == 0 && y == 0 && aspx == 1 && aspy == 1 && w == W && h == H)
    return "";

  var jobj = {
    'x' : Math.round(x * aspx),
    'y' : Math.round(y * aspy),
  };
  if(Wi !== undefined && Hi !== undefined) {
    jobj.framex = Wi;
    jobj.framey = Hi;
    jobj.w = Math.round(w * aspx);
    jobj.h = Math.round(h * aspy);
  }
  else {
    jobj.framex = Math.round(w * aspx);
    jobj.framey = Math.round(h * aspy);
  }

  return base64_encode(JSON.stringify(jobj));
}
</script>

<style type="text/css">
#upload_waiting
{
  display:none;
}

.imgspan {
    margin: 1px;
    border: 2px solid white;
    cursor:pointer;
}

.imgspan:hover {
    border: 2px solid #ffd79d;
}

.imgspan[active] {
    border: 2px solid #ffd79d;
}

#headerimagepreview {
    width: 605px;
    height: 210px;
    overflow: hidden;
}

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

/* The Javascript code will set the aspect ratio of the crop
   area based on the size of the thumbnail preview,
   specified here */
#preview-pane .preview-container {
    width: 620px;
    height: 215px;
    overflow: hidden;
}

img#target {
  max-width: 100%;
}

.dlgpos {
  margin:20px;
  position:fixed;
}

input#prepimglink {
  font-size: 7px;
  width:605px;
  max-width:100%;
}

</style>
@endsection

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
       <div class="postcaption">Мы ждем новый пост!</div>
    </div>
@endsection

@section('content')
<br>
    {{ Form::open('post/new', 'POST', array('id' => 'addPostForm')) }}
        <!-- author -->
        {{ Form::hidden('author_id', $user->id) }}
        <!-- title field -->
        <p>{{ Form::label('title', 'Title') }}</p>
        {{ $errors->first('title', '<p class="error">:message</p>') }}
        <p>{{ Form::text('title', Input::old('title')) }}</p>

        <p>Select header image</p>
        <button type="button" class="btn" id="btopengallery">
            <i class="icon-th icon-black"></i>
            <span>Open gallery</span>
        </button>
        {{ Form::hidden('imageid', Input::old('imageid')) }}
        {{ Form::hidden('imageparam', Input::old('imageparam')) }}
  <div id="headerimagecontainer">
  <div id="headerimagepreview"><img /> </div>
         <a href="#" class="btn" id="btopeneditor">
            <i class="icon-th icon-black"></i>
            <span>Edit preview</span>
         </a>
         <a href="#" class="btn btn-danger" id="btremoveimage">
            <i class="icon-trash icon-black"></i>
            <span>Remove header image</span>
         </a>
  </div>

        <!-- body field -->
        <p>{{ Form::label('body', 'Body') }}</p>
        {{ $errors->first('body', '<p class="error">:message</p>') }}
        <p>{{ Form::textarea('body', Input::old('body')) }}</p>

        <!-- submit button -->
        <button type="submit" class="btn btn-success">
            <i class="icon-ok icon-white"></i>
            <span>Create post</span>
        </button>
    {{ Form::close() }}

<div class='alert'></div>

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

<script type="text/javascript">
var nselectedimage = -1;

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

$(document).ready(function() {
  $(".alert").hide();
  $(document).bind('drop dragover', function (e) {
    e.preventDefault();
  });

  $('#addPostForm').submit(function(e) {
    var rrr = html_parse($('textarea[name="body"]'));
    if(rrr === false) {
      $(".alert").addClass('alert-error').text('Error in html message. Please be careful').show();
      return false;
    }

    var x = $('input[name="title"]');
    x.val(x.val().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'));
  });

  $("#headerimagecontainer").hide();
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

$('#btopeneditor').on('click', function(e) {
  e.preventDefault();
  nselectedimage = $("input[name='imageid']").val();
  $("#aop").dialog({ buttons: {
    "Insert image": function() {
      $( this ).dialog( "close" );
      if($("#prepimglink").val() != "")
        $('input[name="imageparam"]').val($("#prepimglink").val());

      update_header_preview();
    },
    "Cancel": function() {
      nselectedimage = -1;
      $( this ).dialog( "close" );
    }
  } } );
  $('#aop').dialog( { 
    open : function(event, ui) {
      init_jcrop(true);
    } 
  });
  $('#aop').dialog('open');
});

$("#btremoveimage").on('click', function(e) {
  e.preventDefault();

  $('input[name="imageparam"]').val('');
  $('input[name="imageid"]').val('');

  update_header_preview();
});

</script>

@endsection
