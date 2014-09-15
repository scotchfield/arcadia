<?php

function achievement_init() {
    if ( ! defined( 'game_meta_type_achievement' ) ) {
        define( 'game_meta_type_achievement', 200 );
    }

    if ( ! defined( 'game_character_meta_type_achievement' ) ) {
        define( 'game_character_meta_type_achievement', 200 );
    }
}

add_action( 'post_load', 'achievement_init' );


function get_achievement( $id ) {
    return db_fetch(
        'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
        array( game_meta_type_achievement, $id ) );
}

function get_all_achievements() {
    return db_fetch_all(
        'SELECT * FROM game_meta WHERE key_type=?',
        array( game_meta_type_achievement ) );
}

function get_achievements( $character_id ) {
    return db_fetch_all(
        'SELECT a.meta_key AS id, a.meta_value AS meta_value, ' .
            'c.meta_value AS timestamp ' .
            'FROM game_meta AS a, character_meta AS c ' .
            'WHERE a.key_type=? AND c.key_type=? AND ' .
            'a.meta_key=c.meta_key AND c.character_id=? ORDER BY a.meta_key',
        array( game_meta_type_achievement,
               game_character_meta_type_achievement,
               $character_id ),
        'id'
    );
}

function award_achievement( $achievement_id ) {
    global $character;

    if ( FALSE == $character ) {
        return FALSE;
    }

    if ( isset( $character[ 'meta' ][ game_meta_type_achievement ][
                    $achievement_id ] ) ) {
        return FALSE;
    }

    add_character_meta( $character[ 'id' ], game_meta_type_achievement,
        $achievement_id, time() );

    do_action( 'award_achievement',
               array( 'achievement_id' => $achievement_id ) );

    return TRUE;
}
