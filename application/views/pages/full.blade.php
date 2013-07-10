@layout('templates.main')

@section('morelinks')
<meta property="og:type" content="article" /> 
<meta property="og:url" content="{{URL::full()}}" /> 
<meta property="og:title" content="{{$post->title}}" /> 
<meta property="og:image" content="{{$post->img == 0 ? URL::base().'/img/cqlogotop.png' : AuxImage::get_uri($post->img, $post->imgparam)}}" />

    <link rel="stylesheet" type="text/css" href="css/markitup/skin/style.css">
    <link rel="stylesheet" type="text/css" href="css/markitup/style.css">
    <script type="text/javascript" src="js/jquery.markitup.min.js"></script>
    <script type="text/javascript" src="js/editor.js"></script>
    <script type="text/javascript" src="js/uri.min.js"></script>

    <script type="text/javascript">
 var ptitle, psummary, pimage, purl;

    $(document).ready(function() {
        $('textarea').markItUp(comm_editor_settings);
        $('media').parseVideo();

        ptitle = encodeURIComponent($('meta[property="og:title"]').attr('content'));
        psummary = $('#articlemain').text();
        psummary = psummary.length > 128 ? psummary.substr(0, 125) + "..." : psummary;
        psummary = encodeURIComponent(psummary);
        pimage = encodeURIComponent($('meta[property="og:image"]').attr('content'));
        purl = encodeURIComponent($('meta[property="og:url"]').attr('content'));
    });

function fbshare() {
	var shareurl = "http://www.facebook.com/sharer.php?s=100&p[title]=" + ptitle 
		+ "&p[summary]=" + psummary
		+ "&p[url]=" + purl
		+ "&p[images][0]=" + pimage;
	window.open(shareurl,'Share on Facebook','toolbar=0,status=0,width=600,height=325');
}

function vkshare() {
	var shareurl = "http://vkontakte.ru/share.php?url=" + purl
		+ "&title=" + ptitle
		+ "&description=" + psummary
		+ "&image=" + pimage
		+ "&noparse=true";
	window.open(shareurl, 'Опубликовать ссылку во Вконтакте', 'toolbar=0,status=0,width=600,height=325');
}

function gpshare() {
	var shareurl = "https://plus.google.com/share?url=" + purl;
	window.open(shareurl, 'Share on Google+', 'toolbar=0,status=0,width=600,height=325');
}

function tweet() {
	var shareurl = "https://twitter.com/intent/tweet?url=" + purl
		+ "&text=" + ptitle;
	window.open(shareurl, 'Share on Google+', 'toolbar=0,status=0,width=600,height=325');
}

    </script>
<style>

.share {
	display: inline-block;
}

.share a.twitter {
	margin-bottom:-6px;
	opacity: 0.5;
	display: block;
	width: 19px;
	height: 19px;
	background: url("img/share.icons.png") no-repeat scroll -42px 0 transparent;
}

.share a.twitter:hover {
	opacity: 1.0;
}

.share a.vk {
	margin-bottom:-6px;
	opacity: 0.5;
	display: block;
	width: 19px;
	height: 19px;
	background: url("img/share.icons.png") no-repeat scroll -21px 0 transparent;
}

.share a.vk:hover {
	opacity: 1.0;
}

.share a.fb {
	margin-bottom:-6px;
	opacity: 0.5;
	display: block;
	width: 19px;
	height: 19px;
	background: url("img/share.icons.png") no-repeat scroll 0 0 transparent;
}

.share a.fb:hover {
	opacity: 1.0;
}

.share a.gp {
	margin-bottom:-6px;
	opacity: 0.5;
	display: block;
	width: 19px;
	height: 19px;
	background: url("img/share.icons.png") no-repeat scroll -63px 0 transparent;
}

.share a.gp:hover {
	opacity: 1.0;
}

</style>
@endsection

@section('pinned')
<?php
if($post->img) {
    echo '<div class="imagelayer" style="overflow:hidden"><img src="' . AuxImage::get_uri($post->img, $post->imgparam) . '"></div>';
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
$('.alert').html('').hide();

    // attempt to GET the new content
    $.get(BASE+'/comms/' + postid, function(data) {
        $('#load-comms').html(data);
        $('media').parseVideo();
    });
}

function answerto(usrname) {
    $.markItUp( { target:'textarea', replaceWith: usrname + ': ' } );
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
<div class="alert"></div>
@endif

<script type="text/javascript">
$(document).ready(function() {
    $(".alert").hide();
    get_comm({{ $post->id }});

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
                get_comm({{ $post->id }});
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
