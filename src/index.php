<?php

$time_start = microtime( $get_as_float = TRUE );

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

do_action( 'post_load' );

$GLOBALS[ 'character' ] = game_character_active();
if ( FALSE != $character ) {
    $character[ 'meta' ] = get_character_meta( $character[ 'id' ] );
}
do_action( 'character_load' );

if ( isset( $_GET[ 'action' ] ) ) {
    game_set_action( $_GET[ 'action' ] );
}
do_action( 'action_set' );

if ( '' == game_get_action() ) {
    do_action( 'set_default_action' );
}

do_action( 'game_header' );

do_action( 'pre_page_content' );
do_action( 'do_page_content' );
do_action( 'post_page_content' );

do_action( 'game_footer' );

$time_diff = microtime( $get_as_float = TRUE ) - $time_start;
//debug_print( '<p>Page rendered in ' .
//             round( $time_diff, $precision = 4 ) . 's</p>' );
