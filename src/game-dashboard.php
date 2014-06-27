<?php

function game_dashboard_echo_header( $title, $cmd, $id ) {
?>
<div class="row">
  <form role="form" class="form-horizontal" method="get"
        action="<?php echo( GAME_URL ); ?>">
    <input type="hidden" name="action" value="dashboard">
    <input type="hidden" name="cmd" value="<?php echo( $cmd ); ?>">
  <div class="col-md-6"><h3><?php echo( $title ); ?></h3></div>
  <div class="form-group">
    <label for="id" class="col-md-4 text-right">Jump to:</label>
    <div class="col-md-2">
      <input type="number" class="form-control" name="id"
             value="<?php echo( $id ); ?>">
    </div>
  </div>
  </form>
</div>
<form role="form" class="form-horizontal" method="get"
      action="<?php echo( GAME_URL ); ?>">
  <input type="hidden" name="action" value="dashboard">
  <input type="hidden" name="cmd" value="<?php echo( $cmd ); ?>">
  <div class="row">
<?
}

function game_dashboard_echo_footer() {
?>
  </div>
  <div class="row">
    <div class="col-md-12 text-right">
      <button type="submit" class="btn btn-primary">Submit Changes</button>
    </div>
  </div>
</form>
<div class="row">
  <h3 class="text-center">
    <a href="<?php echo( GAME_URL ); ?>?action=dashboard">Back
       to Dashboard</a>
  </h3>
</div>
<?
}

function game_dashboard_echo_form( $label, $input_type, $key, $value ) {
?>
<div class="form-group">
  <label class="col-md-2 text-right"><?php echo( $label ); ?></label>
  <div class="col-md-10">
    <input type="<?php echo( $input_type ); ?>" class="form-control"
           name="<?php echo( $key ); ?>" value="<?php echo( $value ); ?>">
  </div>
</div>
<?
}

function game_dashboard_content() {
    global $game, $user;

    if ( strcmp( 'dashboard', $game->get_action() ) ) {
        return;
    }

    echo '<div class="row">' .
         '<h2>Dashboard (' . $user[ 'user_name' ] . ')</h2>' .
         '</div>';

    $cmd = get_array_if_set( $_GET, 'cmd', FALSE );
    $id = intval( get_array_if_set( $_GET, 'id', 0 ) );

    if ( is_user_dev( $user ) ) {

        if ( ! strcmp( $cmd, 'item' ) ) {

            $item = get_item( $id );

            game_dashboard_echo_header(
                'Item Browser', 'item', $item[ 'id' ] );

            game_dashboard_echo_form(
                'ID', 'number', 'id', $item[ 'id' ] );
            game_dashboard_echo_form(
                'Name', 'text', 'name', $item[ 'name' ] );
            game_dashboard_echo_form(
                'Description', 'text', 'description', $item[ 'description' ] );
            game_dashboard_echo_form(
                'Weight', 'number', 'weight', $item[ 'weight' ] );
            game_dashboard_echo_form(
                'Meta', 'text', 'item_meta', $item[ 'item_meta' ] );

            game_dashboard_echo_footer();

        } else if ( ! strcmp( $cmd, 'npc' ) ) {

            $npc = get_npc_by_id( $id );

            game_dashboard_echo_header(
                'NPC Browser', 'npc', $npc[ 'id' ] );

            game_dashboard_echo_form(
                'ID', 'number', 'id', $npc[ 'id' ] );
            game_dashboard_echo_form(
                'Name', 'text', 'npc_name', $npc[ 'npc_name' ] );
            game_dashboard_echo_form(
                'Description', 'text', 'npc_description',
                $npc[ 'npc_description' ] );
            game_dashboard_echo_form(
                'Defeated', 'text', 'npc_defeated', $npc[ 'npc_defeated' ] );
            game_dashboard_echo_form(
                'State', 'text', 'npc_state', $npc[ 'npc_state' ] );

            game_dashboard_echo_footer();

        } else if ( ! strcmp( $cmd, 'zone' ) ) {

            $zone = get_zone( $id );

            game_dashboard_echo_header(
                'Zone Browser', 'zone', $zone[ 'id' ] );

            game_dashboard_echo_form(
                'ID', 'number', 'id', $zone[ 'id' ] );
            game_dashboard_echo_form(
                'Tag', 'text', 'zone_tag', $zone[ 'zone_tag' ] ) ;
            game_dashboard_echo_form(
                'Title', 'text', 'zone_title', $zone[ 'zone_title' ] );
            game_dashboard_echo_form(
                'Description', 'text', 'zone_description',
                $zone[ 'zone_description' ] );
            game_dashboard_echo_form(
                'Type', 'text', 'zone_type', $zone[ 'zone_type' ] );
            game_dashboard_echo_form(
                'Meta', 'text', 'zone_meta', $zone[ 'zone_meta' ] );

            game_dashboard_echo_footer();

        } else {

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
    }
?>
<div class="col-md-6">
  <h3>Account</h3>

</div>
<?php
}

add_action( 'do_page_content', 'game_dashboard_content' );
