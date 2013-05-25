@if(is_array($comms) && count($comms) > 0)
<div id="removecomm" class="modal hide fade in prompts" style="display: none; ">
    <div class="modal-header">  
        <a class="close" data-dismiss="modal">×</a>  
        <h3>Delete comment?</h3>  
    </div>  
    <div class="modal-body"><p id="removecommtext"></p></div>
    <div class="modal-footer">
        <a href="#" class="btn btn-success btn-dynamic">Yes, delete</a>
        <a href="#" class="btn" data-dismiss="modal">No</a>  
    </div>
</div>

@foreach($comms as $cc)
<div class="commentry"> <span data-commid='{{$cc->id}}'>{{ $cc->body }}</span>
    <div class='posttimestamp'>
        от {{ HTML::link_to_action('account@show', $cc->author()->first()->nickname, array('uid' => $cc->author()->first()->id)) }} , 
        {{ AuxFunc::formatdate($cc->created_at) }} в {{ AuxFunc::formattime($cc->created_at) }}
        <?php
        if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
        {
             echo ' | <a href="#" data-toggle="modal" class="confirm-delete red" data-id="' . $cc->id . '" >[x] Delete</a>';
// echo HTML::link_to_route('comm', 'Edit Comm', array('edit', $cc->id));
// echo HTML::link_to_route('comm', 'Delete Comm', array('delete', $cc->id));
        }
        ?>
    </div>
</div>
@endforeach

@if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )

<script>$('#removecomm').on('show', function() {
    var id = $(this).data('id'),
        removeBtn = $(this).find('.btn-dynamic'),
        bodyTxt = $(this).find('#removecommtext');

    removeBtn.attr('href', '" . URL::to_route("comm", array("delete")) . "/' + id);
    bodyTxt.text($('span[data-commid=' + id + ']').text().substring(0, 50));
});

$('.confirm-delete').on('click', function(e) {
    console.log('confirm.on.click');
    e.preventDefault();

    var id = $(this).data('id');
    $('#removecomm').data('id', id).modal('show');
});
</script>
@endif
@else
	<p>There is no commentaries yet</p>
@endif
