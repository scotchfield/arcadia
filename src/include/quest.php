<?php

function ensure_character_quests() {
    global $character;

    if ( isset( $character[ 'quests' ] ) ) {
        return;
    }

    $character[ 'quests' ] = db_fetch_all(
    'SELECT * FROM character_quests WHERE character_id=?',
        array( $character[ 'id' ] ) );
}

function get_quest( $quest_id ) {
    return db_fetch(
        'SELECT * FROM quests WHERE id=?', array( $quest_id ) );
}

function get_quests_by_npc( $npc_id ) {
    return db_fetch_all(
        'SELECT * FROM quests WHERE npc_id=?', array( $npc_id ),
        $key_assoc = 'id' );
}

function can_character_accept_quest( $quest ) {
    global $character;

    $meta_obj = explode_meta_nokey( $quest[ 'quest_prereq' ] );

    $can_accept = TRUE;
    foreach ( $meta_obj as $meta ) {
        if ( ! eval_predicate( $meta[ 0 ], $meta[ 1 ] ) ) {
            $can_accept = FALSE;
            break;
        }
    }

    if ( ! $can_accept ) {
        return FALSE;
    }

    return TRUE;
}

function get_available_quests_by_npc( $npc_id ) {
    global $character;

    $completed_quests = get_character_completed_quests();
    $active_quests = get_character_active_quests();

    $quest_obj = get_quests_by_npc( $npc_id );

    foreach ( $quest_obj as $k => $quest ) {
        if ( ! can_character_accept_quest( $quest ) ) {
            unset( $quest_obj[ $k ] );
        }
    }

    return $quest_obj;
}

function character_quest_accept( $args ) {
    global $character;

    $quest = get_quest( $args[ 'id' ] );
    $quest_obj = get_character_quests_by_ids( array( $args[ 'id' ] ) );

    $quest_accept = TRUE;

    if ( ! can_character_accept_quest( $quest ) ) {
        $quest_accept = FALSE;
    } else {
        foreach ( $quest_obj as $q ) {
            if ( 0 == $q[ 'completed' ] ) {
                $quest_accept = FALSE;
            } else if ( ( $q[ 'completed' ] > 0 ) &&
                        ( 0 == $quest[ 'repeatable' ] ) ) {
                $quest_accept = FALSE;
            }
        }
    }

    if ( ! $quest_accept ) {
        return FALSE;
    }

    $quest_meta = array();
    $meta_obj = explode_meta_nokey( $quest[ 'quest_acceptmeta' ] );
    foreach ( $meta_obj as $meta ) {
        $meta_key = $meta[ 0 ];
        $meta_value = eval_function( $meta[ 1 ][ 0 ],
                                     array_slice( $meta[ 1 ], 1 ) );
        $quest_meta[] = $meta_key . '=' . $meta_value;
    }

    if ( $quest_accept ) {
        db_execute(
            'INSERT INTO character_quests ' .
                '( character_id, quest_id, completed, quest_meta ) ' .
                'VALUES ( ?, ?, 0, ? )',
            array( $character[ 'id' ], $quest[ 'id' ],
                   join( ';', $quest_meta ) ) );
    }

    $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=questlog';
}

function character_quest_progress( $quest ) {
    global $character;

    $quest_complete = TRUE;

    $meta_obj = explode_meta_nokey( $quest[ 'quest_progress' ] );
    foreach ( $meta_obj as $meta ) {
        if ( ! eval_function( $meta[ 0 ], $meta[ 1 ] ) ) {
            $quest_complete = FALSE;
        }
    }

    return $quest_complete;
}

function character_quest_complete( $args ) {
    global $character;

    $quest = get_quest( $args[ 'id' ] );
    $quest_obj = get_character_quests_by_ids( array( $args[ 'id' ] ) );

    $quest_active = FALSE;
    foreach ( $quest_obj as $q ) {
        if ( 0 == $q[ 'completed' ] ) {
            $quest_active = TRUE;
        }
    }

    if ( $quest_active ) {
        do_action( 'full_character' );

        $current_quest_state = get_character_active_quest( $quest[ 'id' ] );
        $quest_meta_obj = explode_meta( $current_quest_state[ 'quest_meta' ] );

        $quest_complete = TRUE;
        $meta_obj = explode_meta_nokey( $quest[ 'quest_complete' ] );
        foreach ( $meta_obj as $meta ) {
            if ( ! eval_predicate( $meta[ 0 ], $meta[ 1 ] ) ) {
                $quest_complete = FALSE;
            }
        }

        if ( $quest_complete ) {
            db_execute(
                'UPDATE character_quests SET completed=? ' .
                    'WHERE character_id=? AND quest_id=? AND completed=0',
                array( time(), $character[ 'id' ], $quest[ 'id' ] ) );
        }
    }

    $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=npc&id=' .
        $quest[ 'npc_id' ] . '&quest_id=' . $quest[ 'id' ] .
        '&quest_complete';
}

function get_quests_by_ids( $quest_obj ) {
    $place_holders = implode( ',', array_fill( 0, count( $quest_obj ), '?' ) );
    return db_fetch_all(
        'SELECT * FROM quests WHERE id IN (' . $place_holders . ')',
        $quest_obj );
}

function get_character_quests_by_ids( $quest_obj ) {
    global $character;

    return db_fetch_all(
        'SELECT * FROM character_quests ' .
            'WHERE character_id=? AND quest_id IN (?)',
        array( $character[ 'id' ], join( ',', $quest_obj ) ) );
}

function get_character_completed_quests() {
    global $character;

    ensure_character_quests();

    $quest_obj = array();

    foreach ( $character[ 'quests' ] as $quest ) {
        if ( $quest[ 'completed' ] > 0 ) {
            $quest_obj[ $quest[ 'quest_id' ] ] = TRUE;
        }
    }

    return $quest_obj;
}

function get_character_active_quests() {
    global $character;

    ensure_character_quests();

    $quest_obj = array();

    foreach ( $character[ 'quests' ] as $quest ) {
        if ( 0 == $quest[ 'completed' ] ) {
            $quest_obj[ $quest[ 'quest_id' ] ] = TRUE;
        }
    }

    return $quest_obj;
}

function get_character_active_quest( $quest_id ) {
    global $character;

    ensure_character_quests();

    foreach ( $character[ 'quests' ] as $quest ) {
        if ( ( $quest[ 'quest_id' ] == $quest_id ) &&
             ( $quest[ 'completed' ] == 0 ) ) {
            return $quest;
        }
    }

    return FALSE;
}
