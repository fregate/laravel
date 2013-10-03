@layout('admin.main')

@section('morelinks')
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
<link href="css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">

<script src="js/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="js/tag-it.min.js" type="text/javascript" charset="utf-8"></script>

@endsection

@section('manage')
<br><br>

	<?php
echo '<table class="table table-striped table-bordered" id="usertable">';
echo '<thead>';
echo '<tr>';
echo '<th width="20">#UID</th>';
echo '<th width="100">Никнейм</th>';
echo '<th width="50">почта</th>';
echo '<th >Роли</th>';
echo '<th width="100">Идентификаторы</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
		$users = User::order_by("nickname", 'asc')->paginate();
		foreach ($users->results as $u) {
			echo "<tr>";
			echo "<td>" . $u->id . "</td>";
			echo "<td>" . HTML::link(URL::to_action('account@show', array('uid' => $u->id)), $u->nickname ) . "</td>";
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
echo "</tbody>";
echo "</table>";
echo $users->links();
	?>

<div class="alert" style="width:250px"></div>

<script type="text/javascript">
	var ajaxresult;
	var fetchedtags = [];

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

		ajaxresult = ( status == 1 );
	}

	function gettags() {
		return fetchedtags;
	}

    $(document).ready(function() {
$("#manageusers").addClass('active');
    	$(".alert").hide();

		$.ajax({
			url: '{{ URL::to_action("account@getroles") }}',
			success: function(msg) {
					if(msg.status != 0)
						fetchedtags = msg.tags;
				},
			async: false,
			dataType: 'json'
		});


    	$.each($("ul[id^='uroles']"), function() {
    		$(this).tagit({
	        	availableTags: gettags() , //["c++", "java", "php", "javascript", "ruby", "python", "c"],
	        	autocomplete: {delay: 0, minLength: 0},
	        	beforeTagRemoved: function(event, ui) {
	        		if(ui.duringInitialization != true) {
						$.ajax({
							url: '{{ URL::to_action("account@delrole") }}/' 
							     + ui.tag.parent().data("uid") 
							     + '/' + ui.tagLabel,
							success: function(msg) {
									showresult(msg.status, msg.error);
								},
							async: false,
							dataType: 'json'
						});
	        		}
					return ajaxresult;
	        	},

				beforeTagAdded: function(event, ui) {
	                if(ui.duringInitialization != true) {
						$.ajax({
							url: '{{ URL::to_action("account@checkrole") }}/' + ui.tagLabel,
							success: function(msg) {
							        	 showresult(msg.status, msg.error);
							         },
							async: false,
							dataType: 'json'
						});

						return ajaxresult;
	                }
				},

	        	afterTagAdded: function(event, ui) {
	        		if(ui.duringInitialization != true) {
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
