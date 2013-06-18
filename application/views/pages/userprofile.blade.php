@layout('templates.main')

@section('morelinks')
<link href="css/datepicker.css" rel="stylesheet">
<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
@endsection

@section('pinned')
    <div class="imagelayer"><img src="img/x.png"></div>
    <div class="masklayer" style="top: -215px;"><img src="img/m2.png">
    <div class="postcaption">{{ $user->nickname }} (ID #{{ $user->id }})</div></div>
@endsection

@section('content')
<div class='userprofilemenu'>
<ul class="usernav">
  <li class='active'><a href="#"><p>General info</p></a></li>
  <li><a href="#"><p>Posts</p></a></li>
  <li><a href="#"><p>Comments</p></a></li>
  <li><a href="#"><p>Gallery</p></a></li>
</ul>
</div>

    <div class="profile">
        <?php 
        $dc = new DateTime($user->birthday);
        if($dc->getTimestamp() != 0) {
            echo '<h3>Birthday: ';
            if($user->show_year == true)
                echo $dc->format('d-m-Y');
            else
                echo $dc->format('d-m');
            echo '</h3>';
       }

        $dc = new DateTime($user->created_at);
        if($dc->getTimestamp() != 0)
           echo '<p>Registered since: ' . $dc->format('d-m-Y') . '</p>';
        else
            echo '<p>Always here</p>';
        ?>
    </div>

    <div>
    <?php
        $indents = $user->identities()->get();
        $thisuser = !Auth::guest() && Auth::user()->id == $user->id;
        if(count($indents) != 0 && $user->id != 1) {
            echo '<table class="table table-striped table-bordered" id="idntable">  
            <thead>  
              <tr>  
                <th title="network">Network</th>  
                <th width="100px" title="first name">First Name</th>
                <th width="100px" title="last name">Last Name</th>';

            if(!Auth::guest() && Auth::user()->id == $user->id) {
                echo '<th width="100px" title="hidden">Hidden</th>';
                echo '<th width="100px" title="action">Actions</th>';
            }

            echo '</tr></thead><tbody>';

        foreach ($indents as $ind) {
            $cqi = false;
            $netwk;
            $dell = '<td></td>';
            $hide = '';
            if($ind->network == 'club.quant')
            {
                $cqi = true;
                $netwk = HTML::link_to_action('account@show', $ind->network, array('uid' => $ind->user_id));
            }
            else
            {
                $netwk = HTML::link($ind->identity, $ind->network);
                if($thisuser && count($indents) > 1) {
                    $dell = '<td>' . HTML::link_to_route('idn', 'Del', array('del', $ind->id)) . '</td>';
                }
            }

            if($thisuser)
                $hide = '<td>' . HTML::link_to_route('idn', $ind->hidden == true  ? 'Show' : 'Hide', array('hide', $ind->id)) . '</td>';

            if(!$ind->hidden || $thisuser) {
                echo '<tr><td>'
                     . $netwk . '</td><td>'
                     . $ind->first_name . "</td><td>" . $ind->last_name  . '</td>'
                     . $hide . $dell . '</tr>';

            }
        }

        if($cqi == false && $thisuser)
        {
            echo '<tr><td colspan=5><button>add club quant identity</button></td></tr>';
        }
    }

    if($thisuser && $user->id != 1) // add new id
    { 
        echo '<tr ><td ><script src="//ulogin.ru/js/ulogin.js"></script>
              <div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name;providers=facebook,vkontakte,twitter,google,odnoklassniki,mailru,yandex;hidden=openid;redirect_uri='
              . rawurlencode(URL::base() . '/newidn/' . $user->id) .'"></div>
              </td><td colspan="4">Connect this account with other social network accounts</td></tr>';
    }

    if(count($indents) != 0 && $user->id != 1) {
        echo '</tbody></table>';
    }
    echo ' </div>';

if($thisuser) {
    echo '<div>';
    echo Form::open('', 'POST', array('id' => 'changeUserDataForm'));
        echo Form::hidden('user_id', $user->id);
        echo Form::hidden('oldpassword');
        if($user->id != 1) { 
            echo '<p>' . Form::label('newname', 'Change name');
            echo Form::text('newname', Input::old('newname', $user->nickname)) . '</p>';

            echo '<p>' . Form::label('newemail', 'Change email');
            echo Form::email('newemail', Input::old('newemail', $user->email)) . '</p>';
        }

        echo '<p>' . Form::label('newpassword', 'Enter new password');
        echo '<p>' . Form::password('newpassword') . '<span class="alert alert-error" id="equalpwds">passwords are not match!</span></p>';

// remove confirmation password from FORM 
        echo '<p>' . Form::label('confirmpassword', 'Confirm new password');
        echo Form::password('confirmpassword') . '</p>';

        $dc = new DateTime($user->birthday);
        if($dc->getTimestamp() == 0)
            $dc = new DateTime();

        echo '<p>' . Form::label('dp_birth', 'Birthday') . '</p>';

        echo '<input type="text" class="span2" value="'. Input::old('birthday', $dc->format('d-m-Y')) . '" id="dp_birth"';
        if($user->id != 1) { 
            echo  'name="birthday">';
        }
        else {
            echo '>';
        }

        echo '<p>' . Form::label('show_birth_year', 'Show birth year in public') . Form::checkbox('show_birth_year', '1', Input::old('show_birth_year', $user->show_year ? '1' : '0')) . '</p>';

//        echo '<p>' . Form::label('send_email', 'Send email with new data') . Form::checkbox('send_email', '1', Input::old('send_email', '1')) . '</p>';

        echo '<a data-toggle="modal" href="#enterpasswd" class="btn btn-primary">Save changes</a>';
    echo Form::close();

    echo '</div>';
    echo '<div class="alert" id="updatestatus" style="width:250px"></div>';
}

?>

<div id="enterpasswd" class="modal hide fade in prompts" style="display: none; ">  
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>  
        <h3>Confirm changes</h3>  
    </div>  
    <div class="modal-body">
        <p>Please enter current password</p>
        <p><input type=password id="confirmoldpwd"/></p>
    </div>
    <div class="modal-footer">  
        <a href="#" class="btn btn-success btn-primary" id="confirmpwd">Change info</a>  
        <a href="#" class="btn btn-cancel" data-dismiss="modal">No</a>  
    </div>  
</div> 

<script>
$(function() {
    $("#updatestatus").hide();
    $("#equalpwds").hide();

    $('#dp_birth').datepicker({
        format: 'dd-mm-yyyy'
    }).data('datepicker');

    var working = false;

    /* Listening for the submit event of the form: */

    $('#confirmpwd').click(function(e) {
        e.preventDefault();
        $("#enterpasswd").modal('hide');

        if(working)
            return;

        // check for passwords
        if($('#newpassword').val() != '' && $('#newpassword').val() != $('#confirmpassword').val()) {
            $("#equalpwds").show();
            return;
        }
        else {
            $("#equalpwds").hide();
        }

		$("input[name='oldpassword']").val($("#enterpasswd #confirmoldpwd").val());
		$("#enterpasswd #confirmoldpwd").val(null);

	    working = true;
	    $('#submit').val('Working...');

        $.post('{{ URL::to_action("account@update") }}', $('#changeUserDataForm').serialize(), function(msg) {
            working = false;
            $('#submit').val('Save changes');

console.log(msg);

            if(msg.status == 1)
            {
                $("#updatestatus").removeClass('alert-error');
                $("#updatestatus").addClass('alert-success');
            }
            else
            {
                $("#updatestatus").removeClass('alert-success');
                $("#updatestatus").addClass('alert-error');
            }

            $("#updatestatus").text(msg.message);
            $("#updatestatus").show();

        }, 'json');
    });
})

</script>

@endsection

@section('moderation')
<?php
if(!Auth::guest() && Auth::user()->has_role('admin'))
{
    echo HTML::link_to_action('admin@index', 'Admin panel');
}
?>
@endsection
