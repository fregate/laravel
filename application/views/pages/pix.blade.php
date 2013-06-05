@layout('templates.uploader')

@section('morelinks')
<script src="js/jquery.jcrop.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="css/jquery.jcrop.min.css" type="text/css" />

<style type="text/css">
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
    max-height: 85%;
}

#preview-pane {
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

@section('content')

{{$childview}}
    <div id="ajaxmodal" class="modal hide fade in" >
        <div class="modal-header">
            <a class="close" data-dismiss="modal" title="Cancel">×</a>
            <h3>Upload image for header</h3>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
            <a href="#" class="btn btn-success btn-dynamic">Done</a>
            <a href="#" class="btn" data-dismiss="modal">Cancel</a>  
        </div>
    </div>
<!-- <a href="#" data-toggle="modal" class="confirm-delete red" >Upload image through ajax view</a> -->

<script>
$('#ajaxmodal').on('show', function() {
    $.get("{{URL::to_route('pix')}}", function(msg) {
        $(".modal-body").html(msg);
    });
});

$('.confirm-delete').on('click', function(e) {
    e.preventDefault();

    $('#ajaxmodal').modal('show');
});

$('.btn-success').on('click', function(e) {
    e.preventDefault();
    console.log($("#prepimglink").val());
    $('#ajaxmodal').modal('hide');
});

</script>

@endsection
