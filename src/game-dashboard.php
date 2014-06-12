<?php

function game_dashboard_content() {
    global $game, $user;

    if ( strcmp( 'dashboard', $game->get_action() ) ) {
        return;
    }

    echo '<div class="row">' .
         '<h2>Dashboard (' . $user[ 'user_name' ] . ')</h2>' .
         '</div>';

    if ( is_user_dev( $user ) ) {
?>
<div class="col-md-6">
  <h3>Developer Tools</h3>
</div>
<?php
    }
?>
<div class="col-md-6">
  <h3>Account</h3>

</div>
<?php
}

add_action( 'do_page_content', 'game_dashboard_content' );
