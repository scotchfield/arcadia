<?php

function quest_init() {
    if ( ! defined( 'game_meta_type_quest' ) ) {
        define( 'game_meta_type_quest', 204 );
    }

    if ( ! defined( 'game_character_meta_type_quest' ) ) {
        define( 'game_character_meta_type_quest', 204 );
    }
}

add_action( 'post_load', 'quest_init' );


function get_quest( $id ) {
    return get_game_meta( game_meta_type_quest, $id );
}

function get_quest_array( $id_array ) {
    return get_game_meta_array( game_meta_type_quest, $id_array );
}
