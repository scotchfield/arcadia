<?php

define( 'game_user_status_dev', 0 );
define( 'game_user_status_active', 1 );

function get_user_by_name( $name ) {
    return db_fetch( 'SELECT * FROM users WHERE user_name=?', array( $name ) );
}

function get_user_by_id( $id ) {
    return db_fetch( 'SELECT * FROM users WHERE id=?', array( $id ) );
}

function get_user_by_email( $email ) {
    return db_fetch( 'SELECT * FROM users WHERE email=?', array( $email ) );
}

function game_user_logged_in() {
    if ( isset( $_SESSION[ 'u' ] ) ) {
        $user = get_user_by_id( $_SESSION[ 'u' ] );
        return $user;
    }
    return FALSE;
}

function add_user( $name, $pass, $email, $send_email = TRUE ) {
    $name = strip_tags( $name );

    if ( FALSE != get_user_by_name( $name ) ) {
        return FALSE;
    } else if ( strlen( $name ) < 3 ) {
        return FALSE;
    }

    $activate = random_string( 10 );

    db_execute(
        'INSERT INTO users ( user_name, user_pass, email, registered, ' .
            'activation, status ) VALUES ( ?, ?, ?, ?, ?, ? )',
        array( $name, $pass, $email, date( 'Y-m-d H:i:s' ),
            $activate, 0 ) );

    $user_id = db_last_insert_id();

    $text = 'Dear ' . $name . ",\n\nWelcome to " . GAME_NAME . ".\n\n" .
        "To complete your registration, please visit this URL:\n" .
        GAME_URL . 'game-login.php?user=' . $user_id . '&activate=' .
        $activate;

    $text = wordwrap( $text, 70 );

    if ( $send_email ) {
        mail( $email, GAME_NAME . ' Registration', $text,
	          'From: ' . GAME_EMAIL );
    }

    return $user_id;
}

function set_user_status( $user_id, $status ) {
    db_execute(
        'UPDATE users SET status=? WHERE id=?',
        array( $status, $user_id ) );
}

function set_user_max_characters( $user_id, $max_characters ) {
    db_execute(
        'UPDATE users SET max_characters=? WHERE id=?',
        array( $max_characters, $user_id ) );
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
        'SELECT * FROM characters WHERE user_id=?',
        array( $user_id ), $key_assoc = 'id' );
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

    $character_id = add_character( $user[ 'id' ], $_GET[ 'char_name' ] );

    do_action( 'create_character',
               $args = array( 'character_id' => $character_id ) );
}

function user_select_character() {
    global $user;

    if ( FALSE == $user ) {
        return;
    }

    if ( ! isset( $_GET[ 'id' ] ) ) {
        return;
    }

    $character = get_character_by_id( $_GET[ 'id' ] );

    if ( ( FALSE == $character ) ||
         ( $character[ 'user_id' ] != $user[ 'id' ] ) ) {
        return FALSE;
    }

    $character[ 'meta' ] = get_character_meta( $character[ 'id' ] );
    $GLOBALS[ 'character' ] = $character;

    $_SESSION[ 'c' ] = $character[ 'id' ];

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
            if ( ! isset( $obj[ intval( $meta[ 'key_type' ] ) ] ) ) {
                $obj[ intval( $meta[ 'key_type' ] ) ] = array();
            }
            $obj[ intval( $meta[ 'key_type' ] ) ][
                intval( $meta[ 'meta_key' ] ) ] =
                    $meta[ 'meta_value' ];
        }
    } else {
        $meta_obj = db_fetch_all(
            'SELECT * FROM character_meta WHERE character_id=? AND key_type=?',
            array( $id, $type ) );

        $obj = array();
        foreach ( $meta_obj as $meta ) {
            $obj[ intval( $meta[ 'meta_key' ] ) ] = $meta[ 'meta_value' ];
        }
    }

    return $obj;
}

function add_character_meta( $character_id, $key_type,
                             $meta_key, $meta_value ) {
    global $character;

    db_execute(
        'INSERT INTO character_meta ( ' .
            'character_id, key_type, meta_key, meta_value ) ' .
            'VALUES ( ?, ?, ?, ? )',
        array( $character_id, $key_type, $meta_key, $meta_value ) );

    if ( ( isset( $character[ 'id' ] ) ) &&
         ( $character[ 'id' ] == $character_id ) ) {

	if ( ! isset( $character[ 'meta' ] ) ) {
	    $character[ 'meta' ] = array();
        }
	if ( ! isset( $character[ 'meta' ][ $key_type ] ) ) {
	    $character[ 'meta' ][ $key_type ] = array();
	}

        $character[ 'meta' ][ $key_type ][ $meta_key ] = $meta_value;
    }
}

function update_character_meta( $character_id, $key_type,
                                $meta_key, $meta_value ) {
    global $character;

    db_execute(
        'UPDATE character_meta SET meta_value=? WHERE
            character_id=? AND key_type=? AND meta_key=?',
        array( $meta_value, $character_id, $key_type, $meta_key ) );

    if ( ( isset( $character[ 'meta' ] ) ) &&
         ( isset( $character[ 'meta' ][ $key_type ] ) ) ) {
        $character[ 'meta' ][ $key_type ][ $meta_key ] = $meta_value;
    }
}

function clear_all_character_meta( $character_id ) {
    db_execute(
        'DELETE FROM character_meta WHERE character_id=?',
        array( $character_id ) );
}

function ensure_character_meta( $character_id, $key_type, $meta_key ) {
    $obj = db_fetch( 'SELECT * FROM character_meta WHERE ' .
        'character_id=? AND key_type=? AND meta_key=?',
        array( $character_id, $key_type, $meta_key ) );
    if ( FALSE == $obj ) {
        add_character_meta( $character_id, $key_type, $meta_key, '' );
    }
}

function ensure_character_meta_keygroup( $character_id, $key_type,
                                         $default_value, $meta_key_obj ) {
    $place_holders = implode(
        ',', array_fill( 0, count( $meta_key_obj ), '?' ) );
    $args = array_merge(
        array( $character_id, $key_type ), $meta_key_obj );

    $obj = db_fetch_all(
        'SELECT * FROM character_meta WHERE character_id=? AND ' .
            'key_type=? AND meta_key IN (' . $place_holders . ')',
        $args,
        $key_assoc = 'meta_key' );

    foreach ( $meta_key_obj as $k => $v ) {
        if ( isset( $obj[ $v ] ) ) {
            unset( $meta_key_obj[ $k ] );
        }
    }

    // todo: single insert statement here
    foreach ( $meta_key_obj as $meta_key ) {
        add_character_meta(
            $character_id, $key_type, $meta_key, $default_value );
    }
}

function character_meta( $key_type, $meta_key ) {
    global $character;

    if ( ! isset( $character[ 'meta' ] ) ) {
        $character[ 'meta' ] = get_character_meta( $character[ 'id' ] );
    }

    if ( ( ! isset( $character[ 'meta' ][ $key_type ] ) ) &&
         ( defined( $key_type ) ) ) {
        $key_type = constant( $key_type );
    }

    if ( isset( $character[ 'meta' ][ $key_type ] ) &&
         isset( $character[ 'meta' ][ $key_type ][ $meta_key ] ) ) {
        return $character[ 'meta' ][ $key_type ][ $meta_key ];
    }

    return '';
}

function character_meta_int( $key_type, $meta_key ) {
    return intval( character_meta( $key_type, $meta_key ) );
}

function character_meta_float( $key_type, $meta_key ) {
    return floatval( character_meta( $key_type, $meta_key ) );
}

function get_character_items( $id ) {//, $full_description = FALSE ) {
    return db_fetch_all(
        'SELECT * FROM character_items WHERE character_id=?',
        array( $id ),
        $key_assoc = 'id' );
}

function get_character_items_full( $id ) {
    return db_fetch_all(
        'SELECT i.*, ci.* FROM character_items AS ci, items AS i ' .
            'WHERE ci.character_id=? AND ci.item_id=i.id',
        array( $id ),
        $key_assoc = 'id' );
}

function get_character_item_full( $character_id, $item_row_id ) {
    return db_fetch(
        'SELECT i.*, ci.* FROM character_items AS ci, items AS i ' .
            'WHERE ci.character_id=? AND ci.id=? AND ci.item_id=i.id',
        array( $character_id, $item_row_id ),
        $key_assoc = 'id' );
}

function add_character_item( $character_id, $item_id, $item_meta ) {
    db_execute(
        'INSERT INTO character_items ' .
            '( character_id, item_id, charitem_meta ) VALUES ( ?, ?, ? )',
        array( $character_id, $item_id, $item_meta ) );
}

function remove_character_item( $character_id, $item_row_id ) {
    db_execute(
        'DELETE FROM character_items WHERE character_id=? AND id=?',
        array( $character_id, $item_row_id ) );
}

function get_items_from_array( $id_obj ) {
    $place_holders = implode( ',', array_fill( 0, count( $id_obj ), '?' ) );

    $item_obj = db_fetch_all(
        'SELECT * FROM items WHERE id IN (' . $place_holders . ')',
        $id_obj,
        $key_assoc = 'id' );

    return $item_obj;
}

function get_npc_by_id( $id ) {
    return db_fetch( 'SELECT * FROM npcs WHERE id=?', array( $id ) );
}

function get_attacks_from_array( $id_obj ) {
    $place_holders = implode( ',', array_fill( 0, count( $id_obj ), '?' ) );

    $attack_obj = db_fetch_all(
        'SELECT * FROM attacks WHERE id IN (' . $place_holders . ')',
        $id_obj,
        $key_assoc = 'id' );

    return $attack_obj;
}

function character_buy_item( $args ) {
    global $character;

    if ( ( ! isset( $args[ 'zone_id' ] ) ) &&
         ( ! isset( $args[ 'zone_tag' ] ) ) ) {
        return;
    }

    if ( ! isset( $args[ 'item_id' ] ) ) {
        return;
    }

    if ( isset( $args[ 'zone_tag' ] ) ) {
        $zone = get_zone_by_tag( $args[ 'zone_tag' ] );
        $zone_id = $zone[ 'id' ];

        $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=zone&zone_tag=' .
            $args[ 'zone_tag' ];
    } else {
        $zone_id = $args[ 'zone_id' ];

        $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=zone&zone_id=' .
            $zone_id;
    }

    $item = get_zone_item_full( $zone_id, $args[ 'item_id' ] );

    $GLOBALS[ 'game_buy_item' ] = $item;

    do_action( 'buy_item' );

    if ( FALSE != $GLOBALS[ 'game_buy_item' ] ) {
        add_character_item( $character[ 'id' ], $item[ 'id' ], '' );
    }
}

function character_sell_item( $args ) {
    global $character;

    if ( ! isset( $args[ 'item_id' ] ) ) {
        return;
    }

    $item = get_character_item_full( $character[ 'id' ], $args[ 'item_id' ] );

    $GLOBALS[ 'game_sell_item' ] = $item;

    do_action( 'sell_item' );

    if ( FALSE != $GLOBALS[ 'game_sell_item' ] ) {
        remove_character_item( $character[ 'id' ], $item[ 'id' ] );
    }

    if ( isset( $args[ 'zone_tag' ] ) ) {
        $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=zone&zone_tag=' .
            $args[ 'zone_tag' ];
    } else if ( isset( $args[ 'zone_id' ] ) ) {
        $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=zone&zone_id=' .
            $zone_id;
    }
}