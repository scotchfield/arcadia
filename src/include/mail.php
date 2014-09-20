<?php

function mail_init() {
    if ( ! defined( 'game_meta_type_mail' ) ) {
        define( 'game_meta_type_mail', 205 );
    }

    if ( ! defined( 'game_character_meta_type_mail' ) ) {
        define( 'game_character_meta_type_mail', 205 );
    }
}

add_action( 'post_load', 'mail_init' );


function get_mail( $id ) {
    return get_game_meta( game_meta_type_mail, $id );
}

function get_mail_array( $id_array ) {
    return get_game_meta_array( game_meta_type_mail, $id_array );
}
