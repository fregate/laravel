@layout('templates.main')

@section('morelinks')
    <link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
    <link rel="stylesheet" type="text/css" href="css/markitup/style.css">
    <script type="text/javascript" src="js/jquery.markitup.min.js"></script>
    <script type="text/javascript" src="js/editor.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('textarea').markItUp(comm_editor_settings);
    });
    </script>
@endsection

@section('pinned')
<?php
if($post->img) {
    echo '<div class="imagelayer"><img src="' . AuxImage::get_uri($post->img) . '"></div>';
}
else {
    echo '<div class="imagelayer"><img src="img/x.png"></div>';
}
?>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
    <div class="postcaption">{{ $post->title }}</div>
    </div>
@endsection

@section('content')
<!--     <h3 class="postcaption">{{ HTML::link_to_action('post@show', $post->title, array($post->id)) }}</h3> -->
    <div class="postentry">
        <p>{{ $post->body }} </p>

        <div class='posttimestamp'>
            от {{ HTML::link_to_action('account@show', $post->author()->first()->nickname, array('uid' => $post->author()->first()->id)) }}, 
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
            | <a data-toggle="modal" href="#removepost" class="red">[x] Delete post</a>
            @endif
        </div>
        <br>
    </div>

        <!-- if auth, get a cookie with last commid as last_comm_id -->
	    <!-- not properly worked (like lepra)... cookies - only for temp solution -->

    <script type="text/javascript">

    var BASE = "<?php echo URL::base(); ?>";

    function get_comm(postid) {
        // attempt to GET the new content
        $.get(BASE+'/comms/' + postid, function(data) {
            $('#load-comms').html(data);
        });
    }

    </script>

    <div id="load-comms"></div>

    <input onclick="get_comm({{ $post->id }});" type=button value="Refresh Comms">

        @if ( !Auth::guest() )
        <br><br>
        {{ Form::open( '', 'POST', array('id' => 'addCommentForm') ) }}
            <!-- author -->
            {{ Form::hidden('author_id', Auth::user()->id) }}
            <!-- post -->
            {{ Form::hidden('post_id', $post->id) }}
            <!-- body field -->
            <p>{{ Form::textarea('body', Input::old('body')) }}</p>
            <!-- submit button -->
            <p>{{ Form::submit('Add comment', array('id' => 'submit')) }}</p>
        {{ Form::close() }}
        <div class="commerror"></div>
        @endif

    <script type="text/javascript">

$(document).ready(function() {
    get_comm({{ $post->id }});

    /* The following code is executed once the DOM is loaded */
    
    /* This flag will prevent multiple comment submits: */
    var working = false;
    
    /* Listening for the submit event of the form: */
    $('#addCommentForm').submit(function(e) {
        e.preventDefault();

        if(working)
            return false;

        working = true;
        $('#submit').val('Working...');

        $('textarea[name="body"]').encodevalue();

var x = $(this).serialize();
        $('textarea[name="body"]').prop('disabled', true);
//console.log(x);

     // $('textarea').prop('disabled', false);
     // $('textarea[name="body"]').val('');
     // working = false;

        $.post('{{ URL::to_route("comm", array("new")) }}', x, function(msg) {

            $('textarea').prop('disabled', false);

            working = false;
            $('#submit').val('Add comment');
            
            if(msg.status == 1) {
                $('textarea[name="body"]').val('');
                get_comm({{ $post->id }});
            }
            else {
                $.each(msg.errors, function(key, value) {
                    $('.commerror').html(key + ' ' + value); 
                });
            }
        }, 'json');
    });
});

</script>

@endsection
