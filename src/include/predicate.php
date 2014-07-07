<?php

global $valid_predicates;
if ( ! isset( $valid_predicates ) ) {
    $valid_predicates = array(
        'is_character_quest_active',
        'is_character_quest_completed',
        'is_character_quest_meta_diff',
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
    } else {
        debug_print( 'Call to invalid predicate: ' . $predicate );
    }

    return FALSE;
}

function eval_function( $function, $param_obj ) {
    global $valid_functions;

    if ( in_array( $function, $valid_functions ) ) {
        return call_user_func_array( $function, $param_obj );
    } else {
        debug_print( 'Call to invalid function: ' . $function );
    }

    return FALSE;
}

/* --- */

function is_character_quest_active( $quest_id ) {
    $active_quests = get_character_active_quests();    

    if ( isset( $active_quests[ $quest_id ] ) ) {
        return TRUE;
    }

    return FALSE;
}

function is_character_quest_completed( $quest_id ) {
    $completed_quests = get_character_completed_quests();

    if ( isset( $completed_quests[ $quest_id ] ) ) {
        return TRUE;
    }

    return FALSE;
}

function is_character_quest_meta_diff( $key_type, $meta_key,
                                       $quest_meta_key, $diff ) {
    global $character, $quest_meta_obj;

    $n = min( $diff, intval( character_meta( $key_type, $meta_key ) ) - intval(
                         $quest_meta_obj[ $quest_meta_key ] ) );
    $complete = $n >= $diff;

    return $complete;
}