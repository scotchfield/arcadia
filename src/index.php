<?php

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );


$time_start = micro_time();

if ( FALSE == $user ) {
    if ( isset( $custom_start_page ) ) {
        include( GAME_CUSTOM_PATH . $custom_start_page );
    }
    exit;
}

$GLOBALS[ 'character' ] = game_character_active();
if ( FALSE != $character ) {
    $character[ 'meta' ] = get_character_meta( $character[ 'id' ] );
}
do_action( 'character_load' );

if ( isset( $_GET[ 'action' ] ) ) {
    $game->set_action( $_GET[ 'action' ] );
}
do_action( 'action_set' );

if ( ( '' == $game->get_action() ) && ( isset( $custom_default_action ) ) ) {
    $game->set_action( $custom_default_action );
}

do_action( 'game_header' );

do_action( 'pre_page_content' );
do_action( 'do_page_content' );
do_action( 'post_page_content' );

do_action( 'game_footer' );


$time_diff = micro_time() - $time_start;
//debug_print( '<p>Page rendered in ' .
//    number_format( $time_diff, 5, '.', '.' ) . 's</p>' );
