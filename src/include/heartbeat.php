<?php

function heartbeat_init() {
    if ( ! defined( 'game_meta_type_heartbeat' ) ) {
        define( 'game_meta_type_heartbeat', 206 );
    }

    if ( ! defined( 'game_character_meta_type_heartbeat' ) ) {
        define( 'game_character_meta_type_heartbeat', 206 );
    }
}

add_action( 'post_load', 'heartbeat_init' );

function add_heartbeat() {
    global $character;

    if ( ! isset( $character ) || FALSE == $character ) {
        return;
    }

    db_execute( 'DELETE FROM character_meta ' .
        'WHERE character_id=? AND key_type=?',
        array( $character[ 'id' ], game_character_meta_type_heartbeat ) );

    return db_execute( 'INSERT INTO character_meta ' .
        '( character_id, key_type, meta_key, meta_value ) VALUES ' .
        '( ?, ?, 0, ? )',
        array( $character[ 'id' ], game_character_meta_type_heartbeat,
               time() )
    );
}

function get_all_heartbeats() {
    return db_fetch_all(
        'SELECT * FROM character_meta WHERE key_type=?',
        array( game_character_meta_type_heartbeat )
    );
}
