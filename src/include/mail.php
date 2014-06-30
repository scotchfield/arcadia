<?php

define( 'game_mail_unread', 1 );

function get_mail( $character_id ) {
    return db_fetch_all(
        'SELECT * FROM mail WHERE character_id_to=? ORDER BY created',
        array( $character_id ) );
}

function get_mail_by_id( $character_id, $id ) {
    return db_fetch(
        'SELECT * FROM mail WHERE character_id_to=? AND id=?',
        array( $character_id, $id ) );
}

function get_mail_unread_count( $character_id ) {
    return db_fetch(
        'SELECT COUNT( * ) FROM mail WHERE character_id_to=? AND status=?',
        array( $character_id, game_mail_unread ) );
}

