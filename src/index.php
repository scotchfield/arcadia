<?php

$time_start = microtime( $get_as_float = TRUE );

global $game;

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

do_state( 'post_load' );

$GLOBALS[ 'character' ] = game_character_active();
if ( FALSE != $character ) {
    $character[ 'meta' ] = get_character_meta( $character[ 'id' ] );
}
do_state( 'character_load' );

if ( isset( $_GET[ 'state' ] ) ) {
    $game->set_state( $_GET[ 'state' ] );
}
do_state( 'state_set' );

if ( '' == $game->get_state() ) {
    do_state( 'set_default_state' );
}

do_state( 'game_header' );

do_state( 'pre_page_content' );
do_state( 'do_page_content' );
do_state( 'post_page_content' );

do_state( 'game_footer' );

$time_diff = microtime( $get_as_float = TRUE ) - $time_start;
//debug_print( '<p>Page rendered in ' .
//             round( $time_diff, $precision = 4 ) . 's</p>' );
