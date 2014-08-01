<?php

function achievement_init() {
    if ( ! defined( 'game_meta_type_achievement' ) ) {
        define( 'game_meta_type_achievement', 10000 );
    }
}

add_action( 'post_load', 'achievement_init' );


function get_achievement( $achievement_id ) {
    return db_fetch( 'SELECT * FROM achievements WHERE id=?',
                     array( $achievement_id ) );
}

function get_all_achievements() {
    return db_fetch_all( 'SELECT * FROM achievements ORDER BY id', array() );
}

function get_achievements( $character_id ) {
    return db_fetch_all(
        'SELECT * FROM achievements AS a, character_achievements AS c ' .
            'WHERE a.id=c.achievement_id AND c.character_id=? ' .
            'ORDER BY a.id',
        array( $character_id ),
        'id'
    );
}

function award_achievement( $achievement_id ) {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    if ( isset( $character[ 'meta' ][ game_meta_type_achievement ][
                    $achievement_id ] ) ) {
        return;
    }

    add_character_meta( $character[ 'id' ], game_meta_type_achievement,
        $achievement_id, time() );

    do_action( 'award_achievement',
               array( 'achievement_id' => $achievement_id ) );
}
