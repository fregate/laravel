@layout('templates.main')
@section('content')
    <div class="profile">
        <h1>{{ $user->nickname }}</h1>
        <h2>Login: {{ $user->username }}</h2>
        <h2>ID#: {{ $user->id }}</h2>
        <?php 
        $dc = new DateTime($user->created_at);
        if($dc->getTimestamp() != 0)
           echo '<p>Since: ' . $dc->format('d-m-Y') . '</p>';
        else
            echo '<p>Always here</p>';
        ?>
        <p>{{ HTML::link('/', '&larr; Back to main.') }}</p>
    </div>

    <div>
    <?php
        $indents = $user->identities()->get();
        if(count($indents) != 0 && $user->id != 1) {
            echo '<table class="table table-striped table-bordered" id="idntable">  
            <thead>  
              <tr>  
                <th title="network">Network</th>  
                <th width="100px" title="first name">First Name</th>
                <th width="100px" title="last name">Last Name</th>';

            if(!Auth::guest() && Auth::user()->id == $user->id)
                echo '<th width="100px" title="last name">Hidden</th>';

            echo '<th width="100px" title="action">Actions</th>
                  </tr>  
                </thead>  
                <tbody>  ';

        foreach ($indents as $ind) {
            $cqi = false;
            $netwk;
            $dell = '';
            $hide = '';
            if($ind->network == 'club.quant')
            {
                $cqi = true;
                $netwk = HTML::link_to_action('account@show', $ind->network, array('uid' => $ind->user_id));
            }
            else
            {
                $netwk = HTML::link($ind->identity, $ind->network);
                if(!Auth::guest() && Auth::user()->id == $user->id && count($indents) > 1)
                {
                    $dell = HTML::link_to_route('idn', 'Del', array('del', $ind->id));
                    $hide = '<td>' . HTML::link_to_route('idn', $ind->hidden == true  ? 'Show' : 'Hide', array('hide', $ind->id)) . '</td><td>';
                }
            }

            echo '<tr><td>'
                 . $netwk . '</td><td>'
                 . $ind->first_name . "</td><td>" . $ind->last_name  . '</td><td>'
                 . $hide . $dell . '</td></tr>';

            if($cqi == false)
            {
                echo '<tr><td colspan=4>add club quant identity</td></tr>';
            }
        }
    }

    if(!Auth::guest() && Auth::user()->id == $user->id && $user->id != 1) // add new id
        { 
            echo '<tr ><td ><script src="//ulogin.ru/js/ulogin.js"></script>
                  <div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name;providers=facebook,vkontakte,twitter,google,odnoklassniki,mailru,yandex;hidden=openid;redirect_uri='
                  . rawurlencode(URL::base() . '/newidn/' . $user->id) .'"></div>
                  </td><td colspan="3">Connect this account with other networks</td></tr>';
        }

echo '</tbody>
</table>';
  ?>

    </div>

@if (!Auth::guest() && Auth::user()->id == $user->id)
<div>
Change your personal data
</div>
@endif

@endsection

@section('moderation')
<?php
if(!Auth::guest() && User::find(Auth::user()->id)->has_role('admin'))
{
    echo HTML::link_to_action('admin@index', 'Admin panel');
}
?>
@endsection
