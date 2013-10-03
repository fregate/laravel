@layout('admin.main')

@section('manage')
<br><br>
<div class='pins'>

	<form id="addStaticForm" action="{{URL::to_route('snew')}}" method="post">
	<p><label for="title">Page title</label><input id="title" type='text' name='spt_title' style="width:100%" value="Сайт Клуба Квант"></p>
	<p><label for="ta_metas">Add &lt;meta&gt;</label><textarea id="ta_metas" name="spt_metas" style="width:100%"></textarea></p>
	<p><label for="ta_scripts">Add &lt;script&gt; (don't forget to wrap by tags)</label><textarea id="ta_scripts" name="spt_scripts" style="width:100%"></textarea></p>
	<p><label for="ta_styles">Add &lt;style&gt; (don't forget to wrap by tags)</label><textarea id="ta_styles" name="spt_styles" style="width:100%"></textarea></p>
	<p><label for="ta_content">Content of &lt;body&gt;</label><textarea id="ta_content" name="spt_content" style="width:100%" rows="10"></textarea></p>
	<input type='hidden' name='aid' value="{{ Auth::user()->id }}">
	<input type="submit" id="submit" value="Add new page" />
	<input type="button" id="preview" value="Preview Page" />
	</form>
	Созданные ранее статические страницы
<table class="table table-striped table-bordered" id="statictable">  
<thead>  
  <tr>
    <th width="20px">#SID</th>  
    <th>Page title</th>  
    <th width="100px">Author</th>
    <th width="20px"></th>
  </tr>  
</thead>  
<tbody>  

<?php
	$sps = StaticPage::all();
	foreach ($sps as $s) {
		echo "<tr>";
		// link to page and sid
		echo "<td>". $s->id ."</td>";
		echo "<td><a href='" . URL::to_route('s', array($s->id)) . "' target='blank' data-commid='" .$s->id. "'>". $s->title ."</a></td>";
		echo "<td><a href='" . URL::to_action('account@show', array($s->author_id)) . "' target='blank'>". $s->author()->first()->nickname ."</a></td>";
		echo "<td><a title='Remove page' href='#' data-toggle='modal' class='confirm-delete red' data-id='" . $s->id . "' >[x]</a></td>";
		echo "</tr>";
	}
?>

</tbody>
</table>


</div>

<div id="removepage" class="modal hide fade in prompts" style="display: none; ">
    <div class="modal-header">  
        <a class="close" data-dismiss="modal">×</a>  
        <h3>Delete page?</h3>  
    </div>  
    <div class="modal-body"><p id="removepagetitle"></p></div>
    <div class="modal-footer">
        <a href="#" class="btn btn-success btn-dynamic">Yes, delete</a>
        <a href="#" class="btn" data-dismiss="modal">No</a>  
    </div>
</div>

<script>
$(function() {
 $("#managestatic").addClass('active');

 $("#preview").on('click', function(e) {
 	$.post('{{ URL::to("s/preview") }}', $('#addStaticForm').serialize(), function(msg) {
	    var win = window.open('about:blank');
	    with(win.document)
	    {
	      open();
	      write(msg);
	      close();
	    }
	});
 });
})

$('#removepage').on('show', function() {
    var id = $(this).data('id'),
        removeBtn = $(this).find('.btn-dynamic'),
        bodyTxt = $(this).find('#removepagetitle');

    removeBtn.attr('href', '{{ URL::to_route("sdel") }}/' + id);
    bodyTxt.text($('a[data-commid=' + id + ']').text());
});

$('.confirm-delete').on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('id');
    $('#removepage').data('id', id).modal('show');
});

</script>

@endsection
