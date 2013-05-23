@layout('admin.main')

@section('morelinks')
<link href="css/datepicker.css" rel="stylesheet">
<script src="js/bootstrap-datepicker.js"></script>
@endsection

@section('manage')
<br><br>
<div class='pins' >

<table class="table table-striped table-bordered" id="pintable">  
<thead>  
  <tr>  
    <th width="20px">#PID</th>  
    <th>Post title</th>  
    <th width="50px">Image</th>  
    <th width="50px">Start time</th>  
    <th width="50px">End time</th>  
    <th width="100px"></th>
  </tr>  
</thead>  
<tbody>  
<?php
	$pins = Pin::all();
	$ppostids = array();
	foreach ($pins as $pinkey) {
		echo Pin::get_tr($pinkey, true);
		$ppostids[] = $pinkey->post_id;
	}

	$posts = array();
	if(count($ppostids) != 0)
		$posts = Post::where_not_in('id', $ppostids)->get();
	else
		$posts = Post::get();

	echo "<tr id='#newpin'><td>";
	echo "<select onchange='OnPostSelectChange()' style='width: 100px' id='postselect'>";
	foreach($posts as $pkey => $post) {
		echo "<option value='" 
			. $post->title . "' data-image='"
			. AuxImage::get_uri($post->img) . "'>" . $post->id . "</option>";
	}
	echo "</td>";
?>

	<td id="newpinpost" style="white-space:nowrap;text-overflow:hidden">title</td>
	<td id="newpinimg">img</td>

	<form id="addPinForm" method="post" action="">

	<td>
		<input type="text" class="span2" value="" id="dpd1" name='dpd_start' >
	</td>  
	<td>
		<input type="text" class="span2" value="" id="dpd2" name='dpd_end' >
	</td>  
	<td>
		<input type='hidden' id='postidinput' name='postid' ><input type="submit" id="submit" value="Add new pin" />
	</td>  

	</form>

   </tr>  

</tbody>  
</table>  

</div>
<div class='pinerror'></div>
<script>
function OnPostSelectChange() {
	$("#newpinpost").text( $("#postselect").val() );
	$("#newpinimg").html("<img src='" + $("#postselect :selected").data("image") + "' />" );
	$("#postidinput").attr('value', $("#postselect :selected").text() );
}

$(function(){
	OnPostSelectChange();
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	var checkin = $('#dpd1').datepicker({
		format: 'dd-mm-yyyy',
		onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > checkout.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			checkout.setValue(newDate);
		}
		checkin.hide();
		$('#dpd2')[0].focus();
	}).data('datepicker');

	var checkout = $('#dpd2').datepicker({
		format: 'dd-mm-yyyy',
		onRender: function(date) {
			return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		checkout.hide();
	}).data('datepicker');

    var working = false;

    /* Listening for the submit event of the form: */

    $('#addPinForm').submit(function(e){
		e.preventDefault();

		if(working) 
			return false;

		working = true;
		$('#submit').val('Working..');

		/* Sending the form fileds to submit.php: */
		$.post('{{ URL::to_action("pin@new") }}',$(this).serialize(),function(msg){

			working = false;
			$('#submit').val('Add new pin');
			
			if(msg.status == 1)
			{
				$("#postselect :selected").remove();
				$('#pintable > tbody > tr:last').before(msg.html);
				OnPostSelectChange();
			}
			else 
			{
				$.each(msg.errors, function(key, value){
					$('.pinerror').html(key + ' ' + value); 
				});
			}
		}, 'json');
   });
})
</script>
@endsection
