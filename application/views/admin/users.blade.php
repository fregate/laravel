@layout('admin.main')

@section('morelinks')
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">

<script src="js/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/tag-it.js" type="text/javascript" charset="utf-8"></script>

@endsection

@section('manage')
<br><br>
<div class='usersmanage' >

<table class="table table-striped table-bordered" id="usertable">  
<thead>  
  <tr>  
    <th width="20">#UID</th>  
    <th width="100">Username</th>  
    <th width="50">email</th>  
    <th >roles</th>  
    <th width="100">Identities</th>
  </tr>  
</thead>  
<tbody>
	<?php
		$users = User::all();
		foreach ($users as $u) {
			echo "<tr>";
			echo "<td>" . $u->id . "</td>";
			echo "<td>" . $u->nickname . "</td>";
			echo "<td>" . $u->email . "</td>";

			$rolehtml = "<ul id='uroles" . $u->id . "' data-uid='" . $u->id . "'>";
			$uroles = $u->roles;
			foreach ($uroles as $ur) {
				$rolehtml .= "<li>" . $ur->name . "</li>";
			}
			$rolehtml .= "</ul>";

			echo "<td>" . $rolehtml . "</td>";

			$idnshtml = "<ul>";
			$uidns = $u->identities;
			foreach ($uidns as $uid => $ukv) {
				$idnshtml .= "<li>" . $ukv->identity . "</li>";
			}
			$idnshtml .= "</ul>";

			echo "<td>" . $idnshtml . "</td>";
			echo "</tr>";
		}
	?>
</tbody>
</table>

<div class="alert" style="width:250px"></div>

<script type="text/javascript">
	function showresult(status, msg) {
	    if(status == 1)
	    {
    		$(".alert").removeClass('alert-error');
    		$(".alert").addClass('alert-success');
    	}
    	else
    	{
    		$(".alert").removeClass('alert-success');
    		$(".alert").addClass('alert-error');
    	}

		$(".alert").html(msg);
		$(".alert").show();
	}
    $(document).ready(function() {
    	$(".alert").hide();

    	$.each($("ul[id^='uroles']"), function() {
    		$(this).tagit({
	        	availableTags: ["c++", "java", "php", "javascript", "ruby", "python", "c"],
	        	autocomplete: {delay: 0, minLength: 0},
	        	beforeTagRemoved: function(event, ui) {
	        		if(ui.duringInitialization != true)
	        		{
				        $.get('{{ URL::to_action("account@delrole") }}/' + ui.tag.parent().data("uid") + '/' + ui.tagLabel, function(msg) {
				        	showresult(msg.status, msg.error);
				        }, 'json');
	        		}
	        	},
	        	afterTagAdded: function(event, ui) {
	        		if(ui.duringInitialization != true)
	        		{
				        $.get('{{ URL::to_action("account@addrole") }}/' + ui.tag.parent().data("uid") + '/' + ui.tagLabel, function(msg) {
				        	showresult(msg.status, msg.error);
				        }, 'json');
	        		}
	        	}
        	})
        });
    });
</script>

@endsection
