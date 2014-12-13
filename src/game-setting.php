<?php

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

$ag->set_redirect_header( GAME_URL );

foreach ( $_GET as $k => $v ) {
    $ag->set_arg( $k, $v );
}

do_action( 'pre_setting_map' );
do_action( 'do_setting' );
do_action( 'post_setting_map' );

do_action( 'arcadia_end' );

header( 'Location: ' . $ag->redirect_header() );
