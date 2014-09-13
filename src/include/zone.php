<?php

function zone_init() {
    if ( ! defined( 'game_meta_type_zone' ) ) {
        define( 'game_meta_type_zone', 203 );
    }

    if ( ! defined( 'game_character_meta_type_zone' ) ) {
        define( 'game_character_meta_type_zone', 203 );
    }
}

add_action( 'post_load', 'zone_init' );


function get_zone( $id ) {
    return get_game_meta( get_meta_type_zone, $id );
}

function get_zone_array( $id_array ) {
    return get_game_meta_array( get_meta_type_zone, $id_array );
}
