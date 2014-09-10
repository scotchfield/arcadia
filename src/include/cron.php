<?php

function cron_init() {
    if ( ! defined( 'game_meta_type_cron' ) ) {
        define( 'game_meta_type_cron', 10000 );
    }

    do_crons();
}

add_action( 'post_load', 'cron_init' );

function get_crons() {
    return db_fetch_all(
        'SELECT * FROM game_meta WHERE key_type=?',
        array( game_meta_type_cron ),
        'meta_key' );
}

function do_crons() {
    $cron_obj = get_crons();

    foreach ( $cron_obj as $cron ) {
//        debug_print( $cron );
    }
//todo: process crons, return FALSE if something goes wrong
    return TRUE;
}
