<?php

global $valid_predicates;
if ( ! isset( $valid_predicates ) ) {
    $valid_predicates = array(
        'is_character_quest_active' => 'is_character_quest_active',
        'is_character_quest_completed' => 'is_character_quest_completed',
        'is_character_quest_meta_diff' => 'is_character_quest_meta_diff',
    );
}

global $valid_functions;
if ( ! isset( $valid_functions ) ) {
    $valid_functions = array(
        'character_meta' => 'character_meta',
    );
}

function eval_predicate( $predicate, $param_obj ) {
    global $valid_predicates;

    if ( isset( $valid_predicates[ $predicate ] ) ) {
        return call_user_func_array( $valid_predicates[ $predicate ], $param_obj );
    }

    return FALSE;
}

function eval_function( $function, $param_obj ) {
    global $valid_functions;

    if ( isset( $valid_functions[ $function ] ) ) {
        return call_user_func_array( $valid_functions[ $function ], $param_obj );
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
    global $ag, $quest_meta_obj;

    $n = min( $diff, intval( character_meta( $key_type, $meta_key ) ) - intval(
                         $quest_meta_obj[ $quest_meta_key ] ) );
    $complete = $n >= $diff;

    return $complete;
}