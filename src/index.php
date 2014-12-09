<?php

$time_start = microtime( $get_as_float = TRUE );

global $ag;

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

do_action( 'post_load' );

$ag->char = game_character_active();
if ( FALSE != $ag->char ) {
    $ag->char[ 'meta' ] = get_character_meta( $ag->char[ 'id' ] );
}
do_action( 'character_load' );

if ( isset( $_GET[ 'state' ] ) ) {
    $ag->set_state( $_GET[ 'state' ] );
}
foreach( $_GET as $k => $v ) {
    $ag->set_arg( $k, $v );
}
do_action( 'state_set' );

if ( '' == $ag->get_state() ) {
    do_action( 'set_default_state' );
}

do_action( 'game_header' );

do_action( 'pre_page_content' );
do_action( 'do_page_content' );
do_action( 'post_page_content' );

do_action( 'game_footer' );

do_action( 'arcadia_end' );

$time_diff = microtime( $get_as_float = TRUE ) - $time_start;
//debug_print( '<p>Page rendered in ' .
//             round( $time_diff, $precision = 4 ) . 's</p>' );
