@layout('templates.main')

@section('morelinks')
<link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
<link rel="stylesheet" type="text/css" href="css/markitup/style.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<link rel="stylesheet" href="css/jquery.jcrop.min.css" type="text/css" />

<script type="text/javascript" src="js/jquery.markitup.min.js"></script>
<script type="text/javascript" src="js/editor.js"></script>

<script src="bundles/jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="bundles/jupload/js/jquery.iframe-transport.js"></script>
<script src="bundles/jupload/js/jquery.fileupload.js"></script>
<script src="js/jquery.jcrop.min.js"></script>
<script src="js/jquery-ui.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('textarea').markItUp(post_editor_settings);
    });

    var jcrop_api,
        boundx,
        boundy,

        // Grab some information about the preview pane
        $preview, // = $('#preview-pane'),
        $pcnt, // = $('#preview-pane .preview-container'),
        $pimg,// = $('#preview-pane .preview-container img'),

        xsize, // = $pcnt.width(),
        ysize; // = $pcnt.height();

    var H, W;

    function clearCoords(c) {
        $("#prepimglink").val('');
//        $("#prepraw").val($pimg.attr('src'));
    }

    function updatePreview(c)
    {
      if (parseInt(c.w) > 0)
      {
        var rx = xsize / c.w;
        var ry = ysize / c.h;

        $pimg.css({
          width: Math.round(rx * boundx) + 'px',
          height: Math.round(ry * boundy) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });

        $("#prepimglink").val(create_coord(c.x, c.y, c.w, c.h, W / boundx, H / boundy, xsize, ysize));
      }
    }

function base64_encode( data ) {  // Encodes data with MIME base64
  // 
  // +   original by: Tyler Akins (http://rumkin.com)
  // +   improved by: Bayron Guevara

  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
  var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1<<16 | o2<<8 | o3;

    h1 = bits>>18 & 0x3f;
    h2 = bits>>12 & 0x3f;
    h3 = bits>>6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  switch( data.length % 3 ){
    case 1:
      enc = enc.slice(0, -2) + '==';
    break;
    case 2:
      enc = enc.slice(0, -1) + '=';
    break;
  }

  return enc;
}

function create_coord (x, y, w, h, aspx, aspy, W, H) {
  var jobj = {
    'x' : Math.round(x * aspx),
    'y' : Math.round(y * aspy),
    'w' : Math.round(w * aspx),
    'h' : Math.round(h * aspy),
    'framex' : W,
    'framey' : H
  };
  return base64_encode(JSON.stringify(jobj));
}
</script>

<style type="text/css">

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

.ajaxmodal {
    width:auto;
    margin:0px;
    left:3%;
    right:3%;
    top:3%;
    bottom:3%
}

.ajaxmodal .h75 {
    max-height: 75%;
}

#headerimagepreview {
    width: 605px;
    height: 210px;
    overflow: hidden;
}

.ajaxmodal .h85 {
    max-height: 85%;
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
        <button type="button" class="btn" id="btopengallery" data-toggle="modal">
            <i class="icon-th icon-black"></i>
            <span>Open gallery</span>
        </button>
        {{ Form::hidden('imageid', Input::old('imageid')) }}
        {{ Form::hidden('imageparam', Input::old('imageparam')) }}
	<div id="headerimagecontainer">
	<div id="headerimagepreview"><img /> </div>
         <a href="#" class="btn" id="btopeneditor" data-toggle="modal">
            <i class="icon-th icon-black"></i>
            <span>Edit previw</span>
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

    <div id="gallery" class="ajaxmodal modal hide fade in" >
        <div class="modal-header">
            <a class="close" data-dismiss="modal" title="Cancel">×</a>
            <h3>Select image for header</h3>
        </div>
        <div class="modal-body h75" id="gallerycontainer"></div>
        <div class="modal-footer" id="filesDropZone">
            <span class="btn btn-success fileinput-button">
            <i class="icon-plus icon-white"></i>
            <span>Select files...</span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="files[]" multiple="">
            </span>

            <div id="progress" class="progress progress-success progress-striped">
                <div class="bar"></div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-success btn-dynamic" id="selectimg">Select</a>
            <a href="#" class="btn" data-dismiss="modal">Cancel</a>
        </div>
    </div>

    <div id="aop" class="ajaxmodal modal hide fade in" >
        <div class="modal-header">
            <a class="close" data-dismiss="modal" title="Cancel">×</a>
            <h3>Select image area of interest</h3>
        </div>
        <div class="modal-body h85" id="imagecontainer"></div>
        <div class="modal-footer">
            <input type=text readonly id="prepimglink" value="" style="width:605px;max-width:100%">
            <a href="#" class="btn btn-success btn-dynamic" id="selectaop">Set area</a>
        </div>
    </div>

<div id="preview-pane" class="ui-widget-content">
    <div class="preview-container">
      <img src="" class="jcrop-preview" alt="Preview" />
    </div>
</div>

<script type="text/javascript">
var nselectedimage = -1;

function update_header_preview()
{
   if($('input[name="imageparam"]').val() == "" || $('input[name="imageparam"]').val() == undefined || $('input[name="imageid"]').val() < 0 || $('input[name="imageid"]').val() == undefined)
   {
//$('input[name="imageid"]').val(-1);
//$('input[name="imageparam"]').val('');

       $("#headerimagecontainer").hide();
       $("#headerimagepreview > img").attr('src', '')
          .attr('data-idi', -1);
	return;
    }

    $("#headerimagecontainer").show();
    $("#headerimagepreview > img").attr('src', '{{URL::base()}}/image/'+$('input[name="imageid"]').val()+"/"+$('input[name="imageparam"]').val());
//        .attr('data-idi', nselectedimage);
}

$(document).ready(function() {
    $(document).bind('drop dragover', function (e) {
        e.preventDefault();
    });

    $('#addPostForm').submit(function(e) {
        $('textarea[name="body"]').encodevalue();
        var x = $('input[name="title"]');
        x.val(x.val().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'));
    });

    $("#headerimagecontainer").hide();
});

$(function () {
    $('#fileupload').fileupload({
    	url: "{{URL::base()}}/upload/xxx",
        dataType: 'json',
        dropZone: $('#filesDropZone'),
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        minFileSize: 1,
        done: function (e, data) {
            console.log(data.result);

            get_gallery_ajax(true);

	       $('#progress .bar').css('width', '0%');
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
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
                $('#gallery').modal('hide');
            }).on('click', function() {
                $('#gallerycontainer > span[active]').removeAttr('active');
                $(this).attr('active','active');
                nselectedimage = $(this).data('idi');
                $("#selectimg").removeAttr("disabled");
                $('#selectimg').on('click', function(e) {
                    e.preventDefault();
                    if(nselectedimage < 0)
                        return;

                    $('#gallery').modal('hide');
                });
            });
        });
    });
}

$('#gallery').on('show', function() {
    get_gallery_ajax(false);
    $("#selectimg").attr("disabled","disabled");
});

$('#gallery').on('hide', function() {
    if(nselectedimage < 0)
        return;

    $('input[name="imageid"]').val(nselectedimage);

    update_header_preview();
    $('#aop').modal('show');
});

$('#btopengallery').on('click', function(e) {
    e.preventDefault();
nselectedimage = -1;
    $('#gallery').modal('show');
});

$('#selectimg').on('click', function(e) {
    e.preventDefault();
});

////// aop
$('#btopeneditor').on('click', function(e) {
    e.preventDefault();

    $('#aop').modal('show');
});

$('#aop').on('show', function() {
    var idi = $('input[name="imageid"]').val();

    $('#imagecontainer').html('<img src="{{URL::to_route("image")}}/'+idi+'" id="target"/>');
    $('#preview-pane .preview-container img').attr('src', "{{URL::to_route('image')}}/"+idi);
    $("#preview-pane").show().draggable();

    $preview = $('#preview-pane');
    $pcnt = $('#preview-pane .preview-container');
    $pimg = $('#preview-pane .preview-container img');

    xsize = $pcnt.width();
    ysize = $pcnt.height();

    var img = new Image();
    img.src = $pimg.attr('src');

    img.onload = function() {
console.log('img onload');
      H = this.height,
      W = this.width;
	if(jcrop_api != undefined) {
console.log('img setselect');
		jcrop_api.setSelect([0,0,W,H]);
	}
    }

    console.log('init',[xsize,ysize,W,H]);
    $('#target').Jcrop({
      onChange: updatePreview,
      onSelect: updatePreview,
      onRelease: clearCoords,
      aspectRatio: xsize / ysize,
      setSelect: [0, 0, W, H]
    },function(){
      // Use the API to get the real image size
      var bounds = this.getBounds();
      boundx = bounds[0];
      boundy = bounds[1];
      // Store the API in the jcrop_api variable
      jcrop_api = this;
      // Move the preview into the jcrop container for css positioning
//      $preview.appendTo(jcrop_api.ui.holder);
jcrop_api.setSelect([0,0,W,H]);
    });
});

$("#selectaop").on('click', function(e) {
    e.preventDefault();
    $('#aop').modal('hide');
});


$('#aop').on('hide', function() {
    $("#preview-pane").hide();
	if($("#prepimglink").val() != "")
	    $('input[name="imageparam"]').val($("#prepimglink").val());

update_header_preview();
});

$("#btremoveimage").on('click', function(e) {
    e.preventDefault();

    $('input[name="imageparam"]').val('');
    $('input[name="imageid"]').val(-1);

    update_header_preview();
});

</script>

@endsection
