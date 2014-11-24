<?php

$time_start = microtime( $get_as_float = TRUE );

global $ag;

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

do_state( 'post_load' );

$ag->char = game_character_active();
if ( FALSE != $ag->char ) {
    $ag->char[ 'meta' ] = get_character_meta( $ag->char[ 'id' ] );
}
debug_print( $ag->char );
do_state( 'character_load' );

if ( isset( $_GET[ 'state' ] ) ) {
    $ag->set_state( $_GET[ 'state' ] );
}
foreach( $_GET as $k => $v ) {
    $ag->set_state_arg( $k, $v );
}
do_state( 'state_set' );

if ( '' == $ag->get_state() ) {
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
