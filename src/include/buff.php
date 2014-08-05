<?php

function buff_init() {
    if ( ! defined( 'game_meta_type_buff' ) ) {
        define( 'game_meta_type_buff', 20020 );
    }

    if ( ! defined( 'game_character_meta_type_buff' ) ) {
        define( 'game_character_meta_type_buff', 20020 );
    }
}

add_action( 'post_load', 'buff_init' );


function get_buff( $id ) {
    return db_fetch(
        'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
        array( game_meta_type_buff, $id ) );
}

function get_all_buffs() {
    return db_fetch_all(
        'SELECT * FROM game_meta WHERE key_type=? ORDER BY meta_key',
        array( game_meta_type_buff ),
        'meta_key' );
}

function get_buffs( $character_id ) {
    return db_fetch_all(
        'SELECT * FROM game_meta AS a, character_meta AS c ' .
            'WHERE a.key_type=? AND c.key_type=? AND ' .
            'a.meta_key=c.meta_key AND c.character_id=? ORDER BY a.meta_key',
        array( game_meta_type_buff, game_character_meta_type_buff,
               $character_id ),
        'meta_key'
    );
}
