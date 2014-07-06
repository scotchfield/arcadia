<?php

global $valid_predicates;
if ( ! isset( $valid_predicates ) ) {
    $valid_predicates = array(
        'is_character_quest_active',
        'is_character_quest_completed',
    );
}

global $valid_functions;
if ( ! isset( $valid_functions ) ) {
    $valid_functions = array(
        'character_meta',
    );
}

function eval_predicate( $predicate, $param_obj ) {
    global $valid_predicates;

    if ( in_array( $predicate, $valid_predicates ) ) {
        return call_user_func_array( $predicate, $param_obj );
    }

    return FALSE;
}

function eval_function( $function, $param_obj ) {
    global $valid_functions;

    if ( in_array( $function, $valid_functions ) ) {
        return call_user_func_array( $function,  $param_obj );
    }

    return FALSE;
}

/* --- */

function is_character_quest_active( $obj ) {
    $active_quests = get_character_active_quests();    

    if ( isset( $active_quests[ $obj[ 0 ] ] ) ) {
        return TRUE;
    }

    return FALSE;
}

function is_character_quest_completed( $obj ) {
    $completed_quests = get_character_completed_quests();

    if ( isset( $completed_quests[ $obj[ 0 ] ] ) ) {
        return TRUE;
    }

    return FALSE;
}

