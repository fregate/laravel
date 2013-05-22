<!-- if auth && last_comm_id < $cc->id - render as a new comm -->
@if(is_array($comms) && count($comms) > 0)
    <p>Comments</p>
<div id="removecomm" class="modal hide fade in" style="display: none; ">
<div class="modal-header">  
<a class="close" data-dismiss="modal">×</a>  
<h3>Delete comment?</h3>  
</div>  
<div><p id="removecommtext"></p></div>
    <div class="modal-footer">
        <a href="#" class="btn btn-success btn-dynamic">Yes, delete</a>
        <a href="#" class="btn" data-dismiss="modal">No</a>  
    </div>
</div>

    @foreach($comms as $cc)
    	<div class="comm"> <span>{{ $cc->body }}</span><h6> by {{ $cc->author()->first()->nickname }} </h6> 
    		<?php
    		if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
    		{
          echo '<a href="#" data-toggle="modal" class="confirm-delete btn" data-id="' . $cc->id . '" >Delete</a>';
//		    	echo HTML::link_to_route('comm', 'Edit Comm', array('edit', $cc->id));
//		    	echo HTML::link_to_route('comm', 'Delete Comm', array('delete', $cc->id));
    		}
    		?>
    	</div>
    @endforeach
<?php
if( !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator')) )
{
echo "<script>$('#removecomm').on('show', function() {
    console.log('removecomm.on.show');
    var id = $(this).data('id'),
        removeBtn = $(this).find('.btn-dynamic'),
        bodyTxt = $(this).find('#removecommtext');

    removeBtn.attr('href', '" . URL::to_route("comm", array("delete")) . "/' + id);
    bodyTxt.text($('a[data-id=' + id + ']').parent().children('span').text().substring(0, 50));
});

$('.confirm-delete').on('click', function(e) {
    console.log('confirm.on.click');
    e.preventDefault();

    var id = $(this).data('id');
    $('#removecomm').data('id', id).modal('show');
});</script>";
}
    ?>
@else
	<p>There is no commentaries yet</p>
@endif
