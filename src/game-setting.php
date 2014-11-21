<?php

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

do_state( 'post_load' );

$GLOBALS[ 'redirect_header' ] = GAME_URL;


if ( FALSE == $user ) {
    include( GAME_CUSTOM_PATH . $custom_start_page );
    exit;
}

$GLOBALS[ 'character' ] = game_character_active();

if ( FALSE != $character ) {
    $character[ 'meta' ] = get_character_meta( $character[ 'id' ] );
}


if ( ! isset( $_GET[ 'setting' ] ) ) {
    header( 'Location: ' . GAME_URL );
    exit;
}

$setting = $_GET[ 'setting' ];
$args = array();
foreach ( $_GET as $k => $v ) {
    $args[ $k ] = $v;
}

do_state( 'pre_setting_map' );

if ( isset( $setting_map[ $setting ] ) ) {
    call_user_func_array(
        $setting_map[ $setting ], array( $args ) );
} else if ( isset( $custom_setting_map[ $setting ] ) ) {
    call_user_func_array(
        $custom_setting_map[ $setting ], array( $args ) );
}

do_state( 'post_setting_map' );


header( 'Location: ' . $GLOBALS[ 'redirect_header' ] );
