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

function award_buff( $buff_id, $duration ) {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    ensure_character_meta( $character[ 'id' ], game_meta_type_buff,
        $buff_id );
    update_character_meta( $character[ 'id' ], game_meta_type_buff,
        $buff_id, time() + $duration );

    do_action( 'award_buff',
               array( 'buff_id' => $buff_id ) );
}

function check_buff( $buff_id ) {
    global $character;

    if ( FALSE == $character ) {
        return FALSE;
    }

    if ( ! isset( $character[ 'meta' ][ game_character_meta_type_buff ] ) ) {
        return FALSE;
    }

    if ( ! isset( $character[ 'meta' ][ game_character_meta_type_buff
                      ][ $buff_id ] ) ) {
        return FALSE;
    }

    $t = intval( $character[ 'meta' ][ game_character_meta_type_buff
                     ][ $buff_id ] ) - time();

    if ( $t <= 0 ) {
        return FALSE;
    }

    return $t;
}

function remove_buff( $buff_id ) {
    global $character;

    unset( $character[ 'meta' ][ game_character_meta_type_buff ][ $buff_id ] );

    db_execute(
        'DELETE FROM character_meta WHERE key_type=? AND meta_key=?',
        array( game_character_meta_type_buff, $buff_id ) );
}

function update_buffs() {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    if ( ! isset( $character[ 'meta' ][ game_character_meta_type_buff ] ) ) {
        return;
    }

    foreach ( $character[ 'meta' ][ game_character_meta_type_buff ] as
                  $buff_id => $buff_expire ) {
        if ( intval( $buff_expire ) <= time() ) {
            remove_buff( $buff_id );
        }
    }

    do_action( 'apply_buffs' );
}
