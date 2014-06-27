<?php

function game_dashboard_content() {
    global $game, $user;

    if ( strcmp( 'dashboard', $game->get_action() ) ) {
        return;
    }

    echo '<div class="row">' .
         '<h2>Dashboard (' . $user[ 'user_name' ] . ')</h2>' .
         '</div>';

    $cmd = get_array_if_set( $_GET, 'cmd', FALSE );

    if ( is_user_dev( $user ) ) {
?>
<div class="col-md-6">
  <h3>Developer Tools</h3>
    <ul>
      <li><a href="<?php echo( GAME_URL ); ?>?action=dashboard&cmd=item">
        Item Browser</a></li>
      <li><a href="<?php echo( GAME_URL ); ?>?action=dashboard&cmd=npc">
        NPC Browser</a></li>
      <li><a href="<?php echo( GAME_URL ); ?>?action=dashboard&cmd=zone">
        Zone Browser</a></li>
    </ul>
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
