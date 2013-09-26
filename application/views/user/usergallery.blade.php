@layout('templates.profile')

@section('morelinks')
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<script src="bundles/jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="bundles/jupload/js/jquery.iframe-transport.js"></script>
<script src="bundles/jupload/js/jquery.fileupload.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/editor.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("#liimgs").addClass("active");
  $("#upload_waiting").hide();
  $(".fileinput-button").removeAttr('disabled');

  $("#uploadfiles").fileupload({
    url: "{{URL::base()}}/upload/xxx",
    dataType: 'json',
    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
    minFileSize: 1,
    done: function (e, data) {
      console.log(data.result);
//      get_gallery_ajax(true);
      $("#upload_waiting").hide();
      $(".fileinput-button").removeAttr('disabled');
      createimgpreview(data.result[0].idi);
    },
    progressall: function (e, data) {
      $("#upload_waiting").show();
      $(".fileinput-button").attr('disabled', 'disabled');
    }
  });
});

function createimgpreview(imgid) {
  $("#uploaded").append("<div class='imgspan uploaded'><img onclick='fullimage(" 
  + imgid + ")' src='{{URL::to('image')}}/" + imgid +
  "/" + create_coord(194, 200) + "'/></div>");
}

function create_coord (Wi, Hi) {
  var jobj = {
    'x' : 0,
    'y' : 0,
    'w' : '100%',
    'h' : '100%',
    'framex' : Wi,
    'framey' : Hi
  };

  return base64_encode(JSON.stringify(jobj));
}


var mconst = 40;
function resize_dlg(dlgid) {
  if( $( dlgid ).dialog( "isOpen")) {
    $( dlgid ).dialog( "option", "height", window.innerHeight - mconst );
    $( dlgid ).dialog( "option", "width", window.innerWidth - 2 * mconst );
  }
}

$(window).resize(function(){
  resize_dlg("#showimg");
});

$(function () {
  $( "#showimg" ).dialog({
    open: function() {
      resize_dlg("#showimg");
    },
    autoOpen: false,
    dialogClass: "dlgpos",
    resizable:false,
    modal:true,
    draggable:false,
    width:500,
    height:500,
    position: { my: "center top", at: "center top", of: window },
    buttons: [
    {
      text: "Cancel",
      click: function() {
        $( this ).dialog( "close" );
      }
    } ]
  });
});

function fullimage(idi) {
$("#imgcontainer").empty();
$("#imgcontainer").html("<img src='{{ URL::to("image") }}/" + idi + "'/>");
  $( "#showimg" ).dialog("open");
}
</script>
<style>
.dlgpos {
  margin:20px;
  position:fixed;
}
</style>
@endsection

@section('profilesection')
<?php

$useradmin = !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) ||  $user->id == Auth::user()->id;

$images = $user->images()->paginate();
foreach ($images->results as $img) {
	// need to get row height through css class
  echo "<div class='imgspan'><img onclick='fullimage(".$img->id.")' src='"
        . AuxImage::get_uri($img->id, AuxImage::get_thumb_attrs($img->id, 194, 200)) ."' title='[".$img->sx."x".$img->sy."]'></img>";
  if($useradmin) {
    echo '<div><a href="#" data-toggle="modal" class="confirm-delete red" data-idi="' . $img->id . '" >[x] remove</a></div>';
  }
  echo "</div>";
}

echo '<div style="clear:both">' . $images->links() . "</div>";

echo '<div id="removeimg" class="modal hide fade in prompts" style="display: none; ">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>  
        <h3>Delete image?</h3>  
    </div>  
    <div class="modal-footer">
        <a href="#" class="btn btn-success btn-dynamic">Yes, delete</a>
        <a href="#" class="btn" data-dismiss="modal">No</a>  
    </div>
</div>';

echo "<script>$('#removeimg').on('show', function() {
    var id = $(this).data('id'),
        removeBtn = $(this).find('.btn-dynamic'),
        bodyTxt = $(this).find('#removecommtext');

    removeBtn.attr('href', '" . URL::to("image/remove/") . "' + id);
});

$('.confirm-delete').on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('idi');
    $('#removeimg').data('id', id).modal('show');
});
</script>";

?>
<div id="showimg" title="Show image" >
  <div id="imgcontainer"></div>
</div>

<span class="btn btn-success fileinput-button">
<i class="icon-plus icon-white"></i><span>Add images...</span>
<input id="uploadfiles" type="file" name="files[]" multiple>
<span id="upload_waiting"><img src="img/loading.gif"></span>
</span>
<div id="uploaded" style="clear:both"></div>
@endsection
