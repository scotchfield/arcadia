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

function add_heartbeat( $meta_value = array() ) {
    global $character;

    if ( ! isset( $character ) || FALSE == $character ) {
        return FALSE;
    }

    db_execute( 'DELETE FROM character_meta ' .
        'WHERE character_id=? AND key_type=?',
        array( $character[ 'id' ], game_character_meta_type_heartbeat ) );

    return db_execute( 'INSERT INTO character_meta ' .
        '( character_id, key_type, meta_key, meta_value ) VALUES ' .
        '( ?, ?, ?, ? )',
        array( $character[ 'id' ], game_character_meta_type_heartbeat,
               time(), json_encode( $meta_value, JSON_FORCE_OBJECT ) )
    );
}

function get_all_heartbeats() {
    return db_fetch_all(
        'SELECT * FROM character_meta WHERE key_type=?',
        array( game_character_meta_type_heartbeat )
    );
}

function get_heartbeat_characters( $time_delta ) {
    $time_value = time() - $time_delta;

    return db_fetch_all(
        'SELECT c.id, c.character_name, m.meta_key, m.meta_value ' .
            'FROM characters AS c, character_meta AS m ' .
            'WHERE c.id=m.character_id AND meta_key >= ? ' .
            'ORDER BY c.character_name ASC',
        array( $time_value ),
        $assoc = 'id'
    );
}