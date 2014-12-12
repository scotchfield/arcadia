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

$ag->set_redirect_header( GAME_URL );

if ( ! isset( $_GET[ 'setting' ] ) ) {
    header( 'Location: ' . GAME_URL );
    exit;
}

$setting = $_GET[ 'setting' ];
$args = array();
foreach ( $_GET as $k => $v ) {
    // todo: this setting_map below with global and args has to go..
    $GLOBALS[ 'ag' ]->set_arg( $k, $v );
    $args[ $k ] = $v;
}

do_action( 'pre_setting_map' );

if ( isset( $setting_map[ $setting ] ) ) {
    call_user_func_array(
        $setting_map[ $setting ], array( $args ) );
} else if ( isset( $custom_setting_map[ $setting ] ) ) {
    call_user_func_array(
        $custom_setting_map[ $setting ], array( $args ) );
}

do_action( 'post_setting_map' );

do_action( 'arcadia_end' );

header( 'Location: ' . $ag->redirect_header() );
