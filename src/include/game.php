<?php

$GLOBALS[ 'game_action' ] = '';

function game_set_action( $action ) {
    $GLOBALS[ 'game_action' ] = $action;
}

function game_get_action() {
    global $game_action;

    return $game_action;
}

function get_game_meta() {
    $meta_obj = db_fetch_all( 'SELECT * FROM game_meta', array() );

    $obj = array();
    foreach ( $meta_obj as $meta ) {
        if ( ! isset( $obj[ $meta[ 'key_type' ] ] ) ) {
            $obj[ $meta[ 'key_type' ] ] = array();
        }
        $obj[ $meta[ 'key_type' ] ][ $meta[ 'meta_key' ] ] =
            $meta[ 'meta_value' ];
    }

    return $obj;
}
