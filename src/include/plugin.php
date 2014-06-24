<?php

global $game_actions;

if ( ! isset( $game_actions ) ) {
    $game_actions = array();
}


function do_action( $action_id, $args = array() ) {
    global $game_actions;

    foreach ( $game_actions as $action ) {
        if ( ! strcmp( $action[ 0 ], $action_id ) ) {
            if ( 0 == count( $args ) ) {
                $arg_obj = array( $action[ 2 ] );
            } else {
                $arg_obj = array( array_merge( $action[ 2 ], $args ) );
            }

            call_user_func_array( $action[ 1 ], $arg_obj );
        }
    }
}

function add_action( $action_id, $function, $args = array() ) {
    global $game_actions;

    $game_actions[] = array( $action_id, $function, $args );
}

function add_action_priority( $action_id, $function, $args = array() ) {
    global $game_actions;

    array_unshift( $game_actions, array( $action_id, $function, $args ) );
}