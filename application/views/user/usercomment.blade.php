@layout('templates.profile')

@section('morelinks')
<script type="text/javascript" src="js/uri.min.js"></script>
<script type="text/javascript" src="js/editor.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $("#licomms").addClass("active");
  $('media').parseVideo();
});
</script>
@endsection

@section('profilesection')
<?php

$useradmin = !Auth::guest() && Auth::user()->has_any_role(array('admin', 'moderator'));

$comms = $user->comms()->order_by('created_at', 'desc')->paginate();

foreach ($comms->results as $cc) {
echo '<div class="commentry" style="border-bottom: 1px solid #ff9f40;"> <span data-commid=' . $cc->id . '>' . $cc->body . '</span>';
echo '<div class="posttimestamp"><a href="' .URL::to_action("post@show", array($cc->post_id)). '#' .$cc->id.'">#</a>&nbsp;|&nbsp;'
     . AuxFunc::formatdate($cc->created_at) . ' в ' . AuxFunc::formattime($cc->created_at);

    if($useradmin)
    {
         echo ' | <a href="#" data-toggle="modal" class="confirm-delete red" data-id="' . $cc->id . '" >[x] Delete</a>';
    }

    echo '</div></div>';
}

echo $comms->links();

if( $useradmin )
{
if(is_array($comms->results) && count($comms->results) > 0)
{
echo '<div id="removecomm" class="modal hide fade in prompts" style="display: none; ">
    <div class="modal-header">  
        <a class="close" data-dismiss="modal">×</a>  
        <h3>Delete comment?</h3>  
    </div>  
    <div class="modal-body"><p id="removecommtext"></p></div>
    <div class="modal-footer">
        <a href="#" class="btn btn-success btn-dynamic">Yes, delete</a>
        <a href="#" class="btn" data-dismiss="modal">No</a>  
    </div>
</div>';

echo "<script>$('#removecomm').on('show', function() {
    var id = $(this).data('id'),
        removeBtn = $(this).find('.btn-dynamic'),
        bodyTxt = $(this).find('#removecommtext');

    removeBtn.attr('href', '" . URL::to_route("comm", array("delete")) . "/' + id);
    bodyTxt.text($('span[data-commid=' + id + ']').text().substring(0, 50));
});

$('.confirm-delete').on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    $('#removecomm').data('id', id).modal('show');
});
</script>";
}
}

?>

@endsection

