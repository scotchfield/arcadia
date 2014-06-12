<?php

function ensure_character_achievements() {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    if ( isset( $character[ 'achievements' ] ) ) {
        return;
    }

    $character[ 'achievements' ] = get_achievements( $character[ 'id' ] );
}

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

    ensure_character_achievements();

    if ( isset( $character[ 'achievements' ][ $achievement_id ] ) ) {
        return;
    }

    db_execute(
        'INSERT INTO character_achievements ' .
            '( character_id, achievement_id, timestamp ) ' .
            'VALUES ( ?, ?, ? )',
        array( $character[ 'id' ], $achievement_id, time() )
    );

    do_action( 'award_achievement',
               array( 'achievement_id' => $achievement_id ) );
}


