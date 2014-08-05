<?php

function item_init() {
    if ( ! defined( 'game_meta_type_item' ) ) {
        define( 'game_meta_type_item', 20010 );
    }

    if ( ! defined( 'game_character_meta_type_item' ) ) {
        define( 'game_character_meta_type_item', 20010 );
    }
}

add_action( 'post_load', 'item_init' );


function get_item( $id ) {
    return db_fetch(
        'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
        array( game_meta_type_item, $id ) );
}

function get_all_items() {
    return db_fetch_all(
        'SELECT * FROM game_meta WHERE key_type=? ORDER BY meta_key',
        array( game_meta_type_item ),
        'meta_key' );
}

function get_items( $character_id ) {
    return db_fetch_all(
        'SELECT * FROM game_meta AS a, character_meta AS c ' .
            'WHERE a.key_type=? AND c.key_type=? AND ' .
            'a.meta_key=c.meta_key AND c.character_id=? ORDER BY a.meta_key',
        array( game_meta_type_item, game_character_meta_type_item,
               $character_id ),
        'meta_key'
    );
}

function award_item( $meta_key, $meta_value ) {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    add_character_meta( $character[ 'id' ], game_meta_type_item,
        $meta_key, $meta_value );

    do_action( 'award_item',
               array( 'item_id' => $meta_key ) );
}
