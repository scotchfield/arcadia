<?php

global $game_states;

if ( ! isset( $game_states ) ) {
    $game_states = array();
}


function do_action( $action_id, $args = array() ) {
    global $game_states;

    foreach ( $game_states as $state ) {
        if ( $state[ 0 ] != $action_id ) {
            continue;
        }

        if ( $state[ 1 ] ) {
            continue;
        }
        // todo: if $state[ 1 ] == $ag->get_args( 'state' ) or FALSE

        if ( 0 == count( $args ) ) {
            $arg_obj = array( $state[ 3 ] );
        } else {
            $arg_obj = array( array_merge( $state[ 3 ], $args ) );
        }

        call_user_func_array( $state[ 2 ], $arg_obj );
    }
}

function add_state( $state_id, $action_id, $function, $args = array() ) {
    global $game_states;

    $game_states[] = array( $state_id, $action_id, $function, $args );
}

function add_state_priority( $state_id, $action_id, $function,
                             $args = array() ) {
    global $game_states;

    array_unshift( $game_states,
                   array( $state_id, $action_id, $function, $args ) );
}

function remove_state( $state_id, $action_id, $function ) {
    global $game_states;

    foreach ( $game_states as $k => $state ) {
        if ( ( $state[ 0 ] == $state_id ) &&
             ( $state[ 1 ] == $action_id ) &&
             ( $state[ 2 ] == $function ) ) {
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
