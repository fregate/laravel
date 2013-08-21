@layout('templates.profile')

@section('morelinks')
<script type="text/javascript">
$(document).ready(function() {
  $("#liimgs").addClass("active");
});
</script>
@endsection

@section('profilesection')
<?php
$images = $user->images()->paginate();
foreach ($images->results as $img) {
	// need to get row height through css class
  echo "<span class='imgspan' data-idi=".$img->id."><img src='". AuxImage::get_uri($img->id, AuxImage::get_thumb_attrs($img->id, 194, 200)) ."' title='[".$img->sx."x".$img->sy."]'></img></span>";
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

    removeBtn.attr('href', '" . URL::to_route("comm", array("delete")) . "/' + id);
});

$('.confirm-delete').on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('idi');
    $('#removeimg').data('id', id).modal('show');
});
</script>";

?>
@endsection
