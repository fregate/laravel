@layout('templates.main')

@section('morelinks')
<meta property="og:type" content="article" /> 
<meta property="og:url" content="{{URL::full()}}" /> 
<meta property="og:title" content="{{$post->title}}" /> 
<meta property="og:image" content="{{$post->img == 0 ? URL::base().'/img/cqlogotop.png' : AuxImage::get_uri($post->img, $post->imgparam)}}" />

    <link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
    <link rel="stylesheet" type="text/css" href="css/markitup/style.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
    <link rel="stylesheet" href="css/jquery.fileupload-ui.css">
    <link rel="stylesheet" href="css/jquery.jcrop.min.css" type="text/css" />
    <link rel="stylesheet" href="css/sharer.css" type="text/css" />

    <script type="text/javascript" src="js/jquery.markitup.min.js"></script>
    <script type="text/javascript" src="js/editor.js"></script>
    <script type="text/javascript" src="js/uri.min.js"></script>
    <script src="bundles/jupload/js/vendor/jquery.ui.widget.js"></script>
    <script src="bundles/jupload/js/jquery.iframe-transport.js"></script>
    <script src="bundles/jupload/js/jquery.fileupload.js"></script>
    <script src="js/jquery.jcrop.min.js"></script>
    <script src="js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="js/sharer.js" type="text/javascript"></script>
    <script src="js/jquery.jeditable.min.js"></script>

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
});

$(function () {
  $( "#inserturl" ).dialog({
    modal: true,
    autoOpen: false,
    closeOnEscape: true,
    draggable:true
  });
})

$(function () {
  $( "#insertimg" ).dialog({
    modal: true,
    autoOpen: false,
    closeOnEscape: true,
    draggable:true
  });
})

$(function () {
  $( "#insertmedia" ).dialog({
    modal: true,
    autoOpen: false,
    closeOnEscape: true,
    draggable:true
  });
})

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

function url_handler(trg) {
    xxx = [{
      text : "Insert Link", 
      click : function() {
        $( this ).dialog( "close" );
        $.markItUp( { target: trg, replaceWith: '<a href="' + $('#linkurl').val() + '">' + $('#linkpromt').val() + '</a>' } );
        $('#linkurl').val("http://");
      }
    }, {
      text : "Cancel",
      click : function() {
        $( this ).dialog( "close" );
      }
    }];
  $( "#inserturl" ).dialog( "option", "buttons", xxx );
  $('#inserturl').dialog('open');
}

function img_handler(trg) {
    xxx = [{
      text :"Insert Link",
      click : function() {
        $( this ).dialog( "close" );
        $.markItUp( { target: trg, replaceWith: '<img src="' + $('#imgurl').val() + '" alt="' + $('#imgalt').val() + '">' } );
        $('#linkurl').val("http://");
      }
    }, {
      text: "Cancel",
      click : function() {
        $( this ).dialog( "close" );
      }
    }];
  $( "#insertimg" ).dialog( "option", "buttons", xxx );
  $('#insertimg').dialog('open');
}

function media_handler(trg) {
    xxx = [{
      text: "Insert Link",
      click: function() {
        $( this ).dialog( "close" );
        $.markItUp( { target: trg, replaceWith: '<media src="' + $('#mediaurl').val() + '">' } );
        $('#linkurl').val("http://");
      }
    }, {
      text: "Cancel",
      click : function() {
        $( this ).dialog( "close" );
      }
    }];
    console.log(xxx);
  $( "#insertmedia" ).dialog( "option", "buttons", xxx );
  $('#insertmedia').dialog('open');
}

        function img_prompt(h) {
          img_handler('#commArea');
        }

        function url_prompt(h) {
          url_handler('#commArea');
        }

        function media_prompt(h) {
          media_handler('#commArea');
        }

        function glr_prompt(h) {
          glr_handler('#commArea');
        }

        var ces = {
                onTab:  {keepDefault:false, replaceWith:'    '},
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

var jcrop_api,
    boundx,
    boundy,

    // Grab some information about the preview pane
    $preview, // = $('#preview-pane'),
    $pcnt, // = $('#preview-pane .preview-container'),
    $pimg;// = $('#preview-pane .preview-container img'),

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
  console.log([x, y, w, h, aspx, aspy, Wi, Hi, W, H]);

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

  console.log(jobj);

  return base64_encode(JSON.stringify(jobj));
}

$.editable.addInputType('markitup', {
    element : $.editable.types.textarea.element,
    plugin  : function(settings, original) {
        $('textarea', this).markItUp(settings.markitup);
    }
});

@if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
function img_edit() {
  img_handler('textarea[name="bodyPost"]');
}

function url_edit() {
  url_handler('textarea[name="bodyPost"]');
}

function glr_edit() {
  glr_handler('textarea[name="bodyPost"]');
}

function media_edit() {
  media_handler('textarea[name="bodyPost"]');
}
@endif

    $(document).ready(function() {
        $('#commArea').markItUp(ces);
        $('media').parseVideo();

@if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
$("#articlemain").editable('{{ URL::to_route("post", array("edit", $post->id)) }}', {
    type      : 'markitup',
    event     : "editbody",
    cancel    : 'Cancel',
    onblur    : 'ignore',
    submit    : 'OK',
    height    : '500px',
    markitup  : {
                onTab: { keepDefault:false, replaceWith:'    ' },
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
                                {name:'Insert link', className: 'Image', key:'P', beforeInsert: img_edit },
                                {name:'Insert from gallery', className: 'Image', key:'G', beforeInsert: glr_edit }
                        ] },
                        {name:'Link', className: 'Link', key:'L',  beforeInsert: url_edit },
                        {name:'Video', className: 'Video', key: 'M',  beforeInsert: media_edit }
                ]
              },
    name      : 'bodyPost',
    data: function (value, settings) {
        return value.replace(/<br>/gi, '\n');
    },
    onsubmit: function(settings, td) {
      if(!html_parse($('textarea[name="bodyPost"]')))
      {
          $('.alert').html('Error in html message. Please be careful').addClass('alert-error').show(); 
          return false;
      }
      return true;
    }
});

$('#posttitle').editable('{{ URL::to_route("post", array("title", $post->id)) }}', {
    indicator : 'Saving...',
    submit    : 'OK',
    name      : 'title',
    event     : "edittitle"
});

$("#triggerbody").bind("click", function() {
    $("#articlemain").trigger("editbody");
});

$("#triggertitle").bind("click", function() {
    $("#posttitle").trigger("edittitle");
});

$("#triggerimg").bind("click", function() {
});
@endif

    });

    </script>
<style type="text/css">
#upload_waiting
{
  display:none;
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
<?php
if($post->img) {
    echo '<div class="imagelayer" style="overflow:hidden"><img src="' . AuxImage::get_uri($post->img, $post->imgparam) . '"></div>';
}
else {
    $b = IoC::resolve('bungs');
    echo '<div class="imagelayer"><img src="' . $b->get_bung_img() . '"></div>';
}
?>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
    <div class="postcaption" id="posttitle">{{ $post->title }}</div>
    @if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
    <span class="posttimestamp" style="float:right;background:rgba(255, 255, 255, 1);padding:3px">
      <a class='blue' id="triggertitle">[+] Edit title</a>&nbsp;&nbsp;&nbsp;
      <a class='blue' id="triggerimg">[+] Edit image</a>
    </span>
{{ Form::open( 'img/post/' . $post->id, 'POST', array('id' => 'imgEditForm') ) }}
    {{ Form::hidden('img', $post->img) }}
    {{ Form::hidden('imgparam', $post->imgparam) }}
{{ Form::close() }}
    @endif
    </div>
@endsection

@section('content')
<div class="postentry">
    <p id="articlemain" itemprop="description">{{ $post->body }} </p>

    <div class='posttimestamp'>
        <span class="share">
              <a class="twitter" onclick="tweet()" href="javascript: void(0)" title="Tweet this!"></a>
        </span>
        <span class="share">
              <a class="vk" onclick="vkshare()" href="javascript: void(0)" title="Опубликовать во ВКонтакте"></a>
        </span>
        <span class="share">
              <a class="fb" onClick="fbshare()" href="javascript: void(0)" title="Share on Facebook"></a>
        </span>
        <span class="share">
              <a class="gp" onclick="gpshare()" href="javascript: void(0)" title="Share on Google+"></a>
        </span>
        | от {{ HTML::link_to_action('account@show', $post->author()->first()->nickname, array('uid' => $post->author()->first()->id)) }}, 
        {{ AuxFunc::formatdate($post->created_at) }} в {{ AuxFunc::formattime($post->created_at) }}
        @if ( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
            <div id="removepost" class="modal hide fade in prompts" style="display: none">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal">×</a>
                    <h3>Really delete this post?</h3>
                </div>
                <div class="modal-body">
                    <p>It will remove all related commentaries too</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ URL::to_route('post', array('delete', $post->id)) }}" class="btn btn-success">Yes, delete</a>
                    <a href="#" class="btn" data-dismiss="modal">No</a>
                </div>
            </div>
        | <a class="blue" id="triggerbody">[+] Edit post</a> | <a data-toggle="modal" href="#removepost" class="red">[x] Delete post</a>
        @endif
    </div>
    <br>
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

<!-- if auth, get a cookie with last commid as last_comm_id -->
<!-- not properly worked (like lepra)... cookies - only for temp solution -->

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

var BASE = "<?php echo URL::base(); ?>";

function get_comm(postid, navigate) {
$('.alert').html('').hide();

    // attempt to GET the new content
    $.get(BASE+'/comms/' + postid, function(data) {
        $('#load-comms').html(data);
        $('media').parseVideo();

        if(navigate)
        {
          var linkanchor;
          var index = document.URL.lastIndexOf('#');
          if (index != -1)
             linkanchor = document.URL.substring(index );

          if(linkanchor) {
           var targetOffset = $("[name=" + linkanchor + "]").offset().top;
//            document.location.hash = linkanchor;
           $('html, body').animate({scrollTop: targetOffset}, 200);
          }
        }
    });
}

function answerto(usrname) {
    $.markItUp( { target:'textarea', replaceWith: usrname + ': ' } );
}

$(document).ready(function() {
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

</script>

<div id="load-comms"></div>

<input onclick="get_comm({{ $post->id }}, false);" type=button value="Refresh Comms">
@if ( !Auth::guest() )
<br><br>
{{ Form::open( '', 'POST', array('id' => 'addCommentForm') ) }}
    <!-- author -->
    {{ Form::hidden('author_id', Auth::user()->id) }}
    <!-- post -->
    {{ Form::hidden('post_id', $post->id) }}
    <!-- body field -->
    <p>{{ Form::textarea('body', Input::old('body'), array('id' => 'commArea') ) }}</p>
    <!-- submit button -->
    <p>{{ Form::submit('Add comment', array('id' => 'submit')) }}</p>
{{ Form::close() }}
<div class="alert"></div>
@endif

<script type="text/javascript">
$(document).ready(function() {
    $(".alert").hide();
    get_comm({{ $post->id }}, true);

    var working = false;

    $('#addCommentForm').submit(function(e) {
        e.preventDefault();

        if(working)
            return false;

        if(!html_parse($('textarea[name="body"]')))
        {
            $('.alert').html('Error in html message. Please be careful').addClass('alert-error').show(); 
                return false;
        }

        working = true;
        $('#submit').val('Working...');

        var x = $(this).serialize();
        $('textarea[name="body"]').prop('disabled', true);

        $.post('{{ URL::to_route("comm", array("new")) }}', x, function(msg) {

            $('textarea').prop('disabled', false);

            working = false;
            $('#submit').val('Add comment');

            if(msg.status == 1) {
                $('textarea[name="body"]').val('');
                get_comm({{ $post->id }}, false);
            }
            else {
                $.each(msg.errors, function(key, value) {
                    $('.alert').html(key + ' ' + value).addClass('alert-error').show(); 
                });
            }
        }, 'json');
    });
});

</script>

@endsection
