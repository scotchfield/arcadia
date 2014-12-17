<?php

global $ag;

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

$ag->do_action( 'post_load' );

$ag->char = $ag->c( 'user' )->game_character_active();
if ( FALSE != $ag->char ) {
    $ag->char[ 'meta' ] = $ag->c( 'user' )->get_character_meta(
        $ag->char[ 'id' ] );
}
$ag->do_action( 'character_load' );

if ( isset( $_GET[ 'state' ] ) ) {
    $ag->set_state( $_GET[ 'state' ] );
}

$ag->set_redirect_header( GAME_URL );

foreach ( $_GET as $k => $v ) {
    $ag->set_arg( $k, $v );
}

$ag->do_action( 'pre_setting_map' );
$ag->do_action( 'do_setting' );
$ag->do_action( 'post_setting_map' );

$ag->do_action( 'arcadia_end' );

header( 'Location: ' . $ag->redirect_header() );
