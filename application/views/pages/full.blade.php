@layout('templates.main')

@section('morelinks')
<style>
.postheader {
  width:500px;
  height: 300px;
}

</style>
@endsection

@section('content')
    <div class="post">
        <div 
            <?php
            if ( $post->img )
            {
                echo "class='postheader' style='background: url(" . AuxImage::get_uri($post->img) . ")'";
            }
            
            ?>
           >{{ HTML::link_to_action('post@show', $post->title, array($post->id)) }}</div>
        <h5>
	by {{ $post->author()->first()->nickname }}
    <?php
	if ( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
	{ 
        echo "<br>";
        echo '<div id="removepost" class="modal hide fade in" style="display: none; ">  
<div class="modal-header">  
<a class="close" data-dismiss="modal">×</a>  
<h3>Really delete this post?</h3>  
</div>  
<div class="modal-body">
<p>It will remove all related commentaries too</p>
</div>
<div class="modal-footer">  
<a href="' . URL::to_route("post", array("delete", $post->id)) . '" class="btn btn-success">Yes, delete</a>  
<a href="#" class="btn" data-dismiss="modal">No</a>  
</div>  
</div>  
<p><a data-toggle="modal" href="#removepost" class="btn">Delete post</a></p>  ';

//    	echo HTML::link_to_route('post', 'Edit Post', array('edit', $post->id));
//    	echo HTML::link_to_route('post', 'Delete Post', array('delete', $post->id));
    }
    ?>
	</h5>
       <p>{{ $post->body }}</p>
        <p>{{ HTML::link('/', '&larr; Back to index.') }}</p>
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
        <p>{{ Form::label('body', 'Commentary') }}</p>
        {{ $errors->first('body', '<p class="error">:message</p>') }}
        <p>{{ Form::textarea('body', Input::old('body')) }}</p>
        <!-- submit button -->
        <p>{{ Form::submit('Add comment', array('id' => 'submit')) }}</p>
    {{ Form::close() }}
    <div class="commerror"></div>

    <script type="text/javascript">
$(document).ready(function(){
        console.log('document ready');

        get_comm({{ $post->id }});

    /* The following code is executed once the DOM is loaded */
    
    /* This flag will prevent multiple comment submits: */
    var working = false;
    
    /* Listening for the submit event of the form: */
    $('#addCommentForm').submit(function(e){
        e.preventDefault();

        if(working) 
            return false;

        working = true;
        $('#submit').val('Working...');
//        $('span.error').remove();

        /* Sending the form fileds to submit.php: */
        $.post('{{ URL::to_route("comm", array("new")) }}',$(this).serialize(),function(msg){

            working = false;
            $('#submit').val('Add new pin');
            
            if(msg.status == 1)
            {
                $('textarea[id="body"]').val('');
                get_comm({{ $post->id }});
            }
            else 
            {
                $.each(msg.errors, function(key, value){
                    $('.commerror').html(key + ' ' + value); 
                });
            }
        }, 'json');
    });
});

</script>

    @endif

@endsection
