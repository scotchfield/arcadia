<?php

function item_init() {
    if ( ! defined( 'game_meta_type_item' ) ) {
        define( 'game_meta_type_item', 10010 );
    }
}

add_action( 'post_load', 'item_init' );


function get_item( $id ) {
    return db_fetch( 'SELECT * FROM items WHERE id=?', array( $id ) );
}

function get_all_items() {
    return db_fetch_all( 'SELECT * FROM items ORDER BY id', array() );
}

function get_items( $character_id ) {
    return db_fetch_all(
        'SELECT * FROM items AS a, character_meta AS c ' .
            'WHERE a.id=c.meta_key AND c.character_id=? ' .
            'AND c.key_type=? ' .
            'ORDER BY a.id',
        array( $character_id, game_meta_type_item ),
        'id'
    );
}

function award_item( $item_id ) {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    if ( isset( $character[ 'meta' ][ game_meta_type_item ][
                    $item_id ] ) ) {
        return;
    }

    add_character_meta( $character[ 'id' ], game_meta_type_item,
        $item_id, time() );

    do_action( 'award_item',
               array( 'item_id' => $item_id ) );
}
