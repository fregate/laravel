@layout('templates.main')

@section('morelinks')
    <link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
    <link rel="stylesheet" type="text/css" href="css/markitup/style.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
    <script type="text/javascript" src="js/jquery.markitup.min.js"></script>
    <script type="text/javascript" src="js/editor.js"></script>

<script src="bundles/jupload/js/vendor/jquery.ui.widget.js"></script>
<script src="bundles/jupload/js/jquery.iframe-transport.js"></script>
<script src="bundles/jupload/js/jquery.fileupload.js"></script>

<!-- <link rel="stylesheet" type="text/css" href="css/og-grid.css" />
<script src="js/modernizr.custom.js"></script>
 -->
    <script type="text/javascript">
    $(document).ready(function() {
        $('textarea').markItUp(post_editor_settings);
    });

    </script>

<style type="text/css">
/*.am-wrapper{
    float:left;
    position:relative;
    overflow:hidden;
}*/

/*.am-wrapper img{
    position:absolute;
    outline:none;
}*/

.imgspan {
    margin: 2px;
}

#ajaxmodal {
    display: none;
    width:auto;
    margin:0px;
    left:3%;
    right:3%;
    top:3%;
    bottom:3%
}

#ajaxmodal .modal-body {
    max-height: 75%;
}

</style>
@endsection

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png"></div>
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
	<div id="headerimagepreview" class="hide"></div>

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

    <div id="ajaxmodal" class="modal hide fade in" >
        <div class="modal-header">
            <a class="close" data-dismiss="modal" title="Cancel">×</a>
            <h3>Select image for header</h3>
        </div>
        <div class="modal-body am-container" id="am-container"></div>
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

<script type="text/javascript">
$(document).ready(function() {
    $(document).bind('drop dragover', function (e) {
        e.preventDefault();
    });

    $('#addPostForm').submit(function(e) {
        $('textarea[name="body"]').encodevalue();
        var x = $('input[name="title"]');
        x.val(x.val().replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'));
    });
});

$(function () {
    $('#fileupload').fileupload({
    	url: "{{URL::base()}}/upload/xxx",
        dataType: 'json',
        dropZone: $('#filesDropZone'),
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        minFileSize: 1,
        maxFileSize: 8000000,
        done: function (e, data) {
            console.log(data.result);

            get_gallery_ajax();            

	       $('#progress .bar').css('width', '0%');
       
            // $.each(data.result, function (index, file) {
            //     $('<span style="margin-right:2px;border:1px dotted silver;padding:0 3px;border-radius:3px"/>').text(file.name).appendTo('#files');
            // });
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
</script>

<script>
function get_gallery_ajax() {
    $.get("{{URL::to_route('pix')}}/200", function(msg) {
        $(".modal-body").html(msg);

//         var $container  = $("#am-container"),
//         $imgs       = $container.find("img").hide(),
//         totalImgs   = $imgs.length,
//         cnt         = 0;
        
//         $imgs.each(function(i) {
//             var $img    = $(this);
//             $("<img/>").load(function() {
//                 ++cnt;
//                 if( cnt === totalImgs ) {
//                     $imgs.show();
//                     $container.montage({
//                         liquid  : false
// //                        maxh : 250
// //                        fillLastRow : true,
//   //                      margin: 5
//                     });
//                 }
//             }).attr("src",$img.attr("src"));
//         });                 

    });
}

$('#ajaxmodal').on('show', function() {
    get_gallery_ajax();
});

$('#btopengallery').on('click', function(e) {
    e.preventDefault();

    $('#ajaxmodal').modal('show');
});

$('#selectimg').on('click', function(e) {
    e.preventDefault();
//    console.log($("#prepimglink").val());
    $('#ajaxmodal').modal('hide');
});

</script>

@endsection
