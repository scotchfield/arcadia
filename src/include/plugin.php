<?php

global $game_states;

if ( ! isset( $game_states ) ) {
    $game_states = array();
}


function do_state( $state_id, $args = array() ) {
    global $game_states;

    foreach ( $game_states as $state ) {
        if ( $state[ 0 ] == $state_id ) {
            if ( 0 == count( $args ) ) {
                $arg_obj = array( $state[ 2 ] );
            } else {
                $arg_obj = array( array_merge( $state[ 2 ], $args ) );
            }

            call_user_func_array( $state[ 1 ], $arg_obj );
        }
    }
}

function add_state( $state_id, $function, $args = array() ) {
    global $game_states;

    $game_states[] = array( $state_id, $function, $args );
}

function add_state_priority( $state_id, $function, $args = array() ) {
    global $game_states;

    array_unshift( $game_states, array( $state_id, $function, $args ) );
}

function remove_state( $state_id, $function ) {
    global $game_states;

    foreach ( $game_states as $k => $state ) {
        if ( ( $state[ 0 ] == $state_id ) &&
             ( $state[ 1 ] == $function ) ) {
            unset( $game_states[ $k ] );
        }
    }
}

function state_exists( $state_id ) {
    global $game_states;

    foreach ( $game_states as $state ) {
        if ( $state_id == $state[ 0 ] ) {
            return TRUE;
        }
    }

    return FALSE;
}
