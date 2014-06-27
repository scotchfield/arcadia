<?php

function echo_form( $label, $input_type, $value ) {
    echo( '<div class="form-group"><label>' . $label . '</label>' .
          '<input type="' . $input_type . '" class="form-control" value="' .
          $value . '"></div>' );
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
            echo( '<div class="col-md-12"><h3>Item Browser</h3>' .
                  '<form role="form">' );
            echo_form( 'ID', 'number', $item[ 'id' ] );
            echo_form( 'Name', 'text', $item[ 'name' ] );
            echo_form( 'Description', 'text', $item[ 'description' ] );
            echo_form( 'Weight', 'number', $item[ 'weight' ] );
            echo_form( 'Meta', 'text', $item[ 'item_meta' ] );
            echo( '</form></div>' );

        } else if ( ! strcmp( $cmd, 'npc' ) ) {

            $npc = get_npc_by_id( $id );
            echo( '<div class="col-md-12"><h3>NPC Browser</h3>' .
                  '<form role="form">' );
            echo_form( 'ID', 'number', $npc[ 'id' ] );
            echo_form( 'Name', 'text', $npc[ 'npc_name' ] );
            echo_form( 'Description', 'text', $npc[ 'npc_description' ] );
            echo_form( 'Defeated', 'text', $npc[ 'npc_defeated' ] );
            echo_form( 'State', 'text', $npc[ 'npc_state' ] );
            echo( '</form></div>' );

        } else if ( ! strcmp( $cmd, 'zone' ) ) {

            $zone = get_zone( 1);//$id );
            echo( '<div class="col-md-12"><h3>Zone Browser</h3>' .
                  '<form role="form">' );
            echo_form( 'ID', 'number', $zone[ 'id' ] );
            echo_form( 'Tag', 'text', $zone[ 'zone_tag' ] ) ;
            echo_form( 'Title', 'text', $zone[ 'zone_title' ] );
            echo_form( 'Description', 'text', $zone[ 'zone_description' ] );
            echo_form( 'Type', 'text', $zone[ 'zone_type' ] );
            echo_form( 'Meta', 'text', $zone[ 'zone_meta' ] );
            echo( '</form></div>' );

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
