<?php

define( 'game_user_status_dev', 0 );
define( 'game_user_status_active', 1 );

function get_user_by_name( $name ) {
    return db_fetch( 'SELECT * FROM users WHERE user_name=?', array( $name ) );
}

function get_user_by_id( $id ) {
    return db_fetch( 'SELECT * FROM users WHERE id=?', array( $id ) );
}

function game_user_logged_in() {
    if ( isset( $_SESSION[ 'u' ] ) ) {
        $user = get_user_by_id( $_SESSION[ 'u' ] );
        return $user;
    }
    return FALSE;
}

function add_user( $name, $pass, $email ) {
    db_execute(
        'INSERT INTO users ( user_name, user_pass, email, registered, ' .
            'activation, status ) VALUES ( ?, ?, ?, ?, ?, ? )',
        array( $name, $pass, $email, date( 'Y-m-d H:i:s' ), 'abcdefg', 0 ) );

    return db_last_insert_id();
}

function is_user_dev( $user ) {
    if ( ! isset( $user[ 'status' ] ) ) {
        return FALSE;
    }

    if ( get_bit( $user[ 'status' ], game_user_status_dev ) ) {
        return TRUE;
    }

    return FALSE;
}

function is_user_active( $user ) {
    if ( ! isset( $user[ 'status' ] ) ) {
        return FALSE;
    }

    if ( get_bit( $user[ 'status' ], game_user_status_active ) ) {
       return TRUE;
    }

    return FALSE;
}

function get_characters_for_user( $user_id ) {
    return db_fetch_all(
        'SELECT * FROM characters WHERE user_id=?', array( $user_id ) );
}

function get_character_by_name( $name ) {
    return db_fetch(
        'SELECT * FROM characters WHERE character_name=?',
        array( $name ) );
}

function get_character_by_id( $id ) {
    return db_fetch( 'SELECT * FROM characters WHERE id=?', array( $id ) );
}

function add_character( $user_id, $name ) {
    db_execute(
        'INSERT INTO characters ( user_id, character_name ) VALUES ( ?, ? )',
            array( $user_id, $name ) );

    return db_last_insert_id();
}

function user_create_character() {
    global $user;

    if ( FALSE == $user ) {
        return;
    }

    if ( ! isset( $_GET[ 'char_name' ] ) ) {
        return;
    }
//todo: nonce
//todo: max char count/user

    add_character( $user[ 'id' ], $_GET[ 'char_name' ] );
}

function user_select_character() {
    global $user;

    if ( FALSE == $user ) {
        return;
    }

    if ( ! isset( $_GET[ 'id' ] ) ) {
        return;
    }

    $char = get_character_by_id( $_GET[ 'id' ] );

    if ( ( FALSE == $char ) || ( $char[ 'user_id' ] != $user[ 'id' ] ) ) {
        return FALSE;
    }

    $GLOBALS[ 'character' ] = $char;
    $_SESSION[ 'c' ] = $char[ 'id' ];

    do_action( 'select_character' );
}

function user_clear_character() {
    global $user;

    if ( FALSE == $user ) {
        return;
    }

    unset( $_SESSION[ 'c' ] );
}

function game_character_active() {
    if ( isset( $_SESSION[ 'c' ] ) ) {
        $character = get_character_by_id( $_SESSION[ 'c' ] );
        return $character;
    }
    return FALSE;
}

function get_character_meta( $id, $type = FALSE ) {
    if ( FALSE == $type ) {
        $meta_obj = db_fetch_all(
            'SELECT * FROM character_meta WHERE character_id=?',
            array( $id ) );

        $obj = array();
        foreach ( $meta_obj as $meta ) {
            if ( ! isset( $obj[ $meta[ 'key_type' ] ] ) ) {
                $obj[ $meta[ 'key_type' ] ] = array();
            }
            $obj[ $meta[ 'key_type' ] ][ $meta[ 'meta_key' ] ] =
                $meta[ 'meta_value' ];
        }
    } else {
        $meta_obj = db_fetch_all(
            'SELECT * FROM character_meta WHERE character_id=? AND key_type=?',
            array( $id, $type ) );

        $obj = array();
        foreach ( $meta_obj as $meta ) {
            $obj[ $meta[ 'meta_key' ] ] = $meta[ 'meta_value' ];
        }
    }

    return $obj;
}

function add_character_meta( $character_id, $key_type,
                             $meta_key, $meta_value ) {
    db_execute(
        'INSERT INTO character_meta ( ' .
            'character_id, key_type, meta_key, meta_value ) ' .
            'VALUES ( ?, ?, ?, ? )',
        array( $character_id, $key_type, $meta_key, $meta_value ) );
}

function update_character_meta( $character_id, $key_type,
                                $meta_key, $meta_value ) {
    db_execute(
        'UPDATE character_meta SET meta_value=? WHERE
            character_id=? AND key_type=? AND meta_key=?',
        array( $meta_value, $character_id, $key_type, $meta_key ) );
}

function ensure_character_meta( $character_id, $key_type, $meta_key ) {
    $obj = db_fetch( 'SELECT * FROM character_meta WHERE ' .
        'character_id=? AND key_type=? AND meta_key=?',
        array( $character_id, $key_type, $meta_key ) );
    if ( FALSE == $obj ) {
        add_character_meta( $character_id, $key_type, $meta_key, '' );
    }
}

function character_meta( $key_type, $meta_key ) {
    global $character;

    if ( ! isset( $character[ 'meta' ] ) ) {
        $character[ 'meta' ] = get_character_meta( $character[ 'id' ] );
    }

    if ( isset( $character[ 'meta' ][ $key_type ] ) &&
         isset( $character[ 'meta' ][ $key_type ][ $meta_key ] ) ) {
        return $character[ 'meta' ][ $key_type ][ $meta_key ];
    }

    return '';
}

function get_character_items( $id, $full_description = FALSE ) {
    $item_obj = db_fetch_all(
        'SELECT * FROM character_items WHERE character_id=?',
        array( $id ),
        $key_assoc = 'id' );

    if ( TRUE == $full_description ) {
        $id_obj = array();
        $pdo_obj = array(); // todo: better way??
        foreach ( $item_obj as $item ) {
            if ( ! in_array( $item[ 'item_id' ], $id_obj ) ) {
                $id_obj[] = $item[ 'item_id' ];
                $pdo_obj[] = '?';
            }
        }

        $desc_obj = db_fetch_all(
            'SELECT * FROM items WHERE id IN ( ' .
                join( ',', $pdo_obj ) . ' )',
            $id_obj,
            $key_assoc = 'id' );

        foreach ( array_keys( $item_obj ) as $item_key ) {
            foreach ( $desc_obj[ $item_obj[ $item_key ][ 'item_id' ] ] as
                      $k => $v ) {
                if ( ! isset( $item_obj[ $item_key ][ $k ] ) ) {
                    $item_obj[ $item_key ][ $k ] = $v;
                }
            }
        }
    }

    return $item_obj;
}

function add_character_item( $character_id, $item_id, $item_state ) {
    db_execute(
        'INSERT INTO character_items ' .
            '( character_id, item_id, item_state ) VALUES ( ?, ?, ? )',
        array( $character_id, $item_id, $item_state ) );
}

function remove_character_item( $character_id, $item_row_id ) {
    db_execute(
        'DELETE FROM character_items WHERE character_id=? AND id=?',
        array( $character_id, $item_row_id ) );
}

function get_items_from_array( $id_obj ) {
    $pdo_obj = array(); // todo: better way??
    for ( $i = 0 ; $i < count( $id_obj ) ; $i++ ) {
        $pdo_obj[] = '?';
    }

    $item_obj = db_fetch_all(
        'SELECT * FROM items WHERE id IN ( ' .
            join( ',', $pdo_obj ) . ' )',
        $id_obj,
        $key_assoc = 'id' );

    return $item_obj;
}

function get_npc_by_id( $id ) {
    return db_fetch( 'SELECT * FROM npcs WHERE id=?', array( $id ) );
}

function get_attacks_from_array( $id_obj ) {
    $pdo_obj = array(); // todo: better way??
    for ( $i = 0 ; $i < count( $id_obj ) ; $i++ ) {
        $pdo_obj[] = '?';
    }

    $attack_obj = db_fetch_all(
        'SELECT * FROM attacks WHERE id IN ( ' .
            join( ',', $pdo_obj ) . ' )',
        $id_obj,
        $key_assoc = 'id' );

    return $attack_obj;
}