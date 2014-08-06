<?php

$GLOBALS[ 'game_action' ] = '';

function game_set_action( $action ) {
    $GLOBALS[ 'game_action' ] = $action;
}

function game_get_action() {
    return $GLOBALS[ 'game_action' ];
}

function get_game_meta( $key_type, $meta_key ) {
    return db_fetch(
        'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
        array( $key_type, $meta_key ) );
}

function get_game_meta_keytype( $key_type ) {
    return db_fetch_all(
        'SELECT * FROM game_meta WHERE key_type=?',
        array( $key_type ),
        $key_assoc = 'meta_key' );
}

function get_game_meta_all() {
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

function update_game_meta( $key_type, $meta_key, $meta_value ) {
    db_execute(
        'UPDATE game_meta SET meta_value=? WHERE key_type=? AND meta_key=?',
        array( $meta_value, $key_type, $meta_key ) );
}
