<?php

class ArcadiaUser extends ArcadiaComponent {

    const USER_STATUS_DEV = 0;
    const USER_STATUS_ACTIVE = 1;

    private $ag;

    public function __construct( $ag ) {
        $this->ag = $ag;
    }

    function get_user_by_name( $name ) {
        return $this->ag->c( 'db' )->db_fetch(
            'SELECT * FROM users WHERE user_name=?', array( $name ) );
    }

    function get_user_by_id( $id ) {
        return $this->ag->c( 'db' )->db_fetch(
            'SELECT * FROM users WHERE id=?', array( $id ) );
    }

    function get_user_by_email( $email ) {
        return $this->ag->c( 'db' )->db_fetch(
            'SELECT * FROM users WHERE email=?', array( $email ) );
    }

    function game_user_logged_in() {
        if ( isset( $_SESSION[ 'u' ] ) ) {
            $user = $this->get_user_by_id( $_SESSION[ 'u' ] );
            return $user;
        }
        return FALSE;
    }

    function add_user( $name, $pass, $email, $send_email = TRUE ) {
        $name = strip_tags( $name );

        if ( FALSE != $this->get_user_by_name( $name ) ) {
            return FALSE;
        } else if ( strlen( $name ) < 3 ) {
            return FALSE;
        }

        $activate = $this->ag->c( 'common' )->random_string( 10 );

        $this->ag->c( 'db' )->db_execute(
            'INSERT INTO users ( user_name, user_pass, email, registered, ' .
                'activation, status ) VALUES ( ?, ?, ?, ?, ?, ? )',
            array( $name, $pass, $email, date( 'Y-m-d H:i:s' ),
                $activate, 0 ) );

        $user_id = $this->ag->c( 'db' )->db_last_insert_id();

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
        $this->ag->c( 'db' )->db_execute(
            'UPDATE users SET status=? WHERE id=?',
            array( $status, $user_id ) );
    }

    function set_user_max_characters( $user_id, $max_characters ) {
        $this->ag->c( 'db' )->db_execute(
            'UPDATE users SET max_characters=? WHERE id=?',
            array( $max_characters, $user_id ) );
    }

    function is_user_dev( $user ) {
        if ( ! isset( $user[ 'status' ] ) ) {
            return FALSE;
        }

        if ( $this->ag->c( 'common' )->get_bit( $user[ 'status' ],
                                                SELF::USER_STATUS_DEV ) ) {
            return TRUE;
        }

        return FALSE;
    }

    function is_user_active( $user ) {
        if ( ! isset( $user[ 'status' ] ) ) {
            return FALSE;
        }

        if ( $this->ag->c( 'common' )->get_bit( $user[ 'status' ],
                                                SELF::USER_STATUS_ACTIVE ) ) {
            return TRUE;
        }

        return FALSE;
    }

    function get_characters_for_user( $user_id ) {
        return $this->ag->c( 'db' )->db_fetch_all(
            'SELECT * FROM characters WHERE user_id=?',
            array( $user_id ), $key_assoc = 'id' );
    }

    function get_character_by_name( $name ) {
        return $this->ag->c( 'db' )->db_fetch(
            'SELECT * FROM characters WHERE character_name=?',
            array( $name ) );
    }

    function get_character_by_id( $id ) {
        return $this->ag->c( 'db' )->db_fetch(
            'SELECT * FROM characters WHERE id=?', array( $id ) );
    }

    function add_character( $user_id, $name ) {
        $this->ag->c( 'db' )->db_execute(
            'INSERT INTO characters ( user_id, character_name ) ' .
                'VALUES ( ?, ? )',
            array( $user_id, $name ) );

        return $this->ag->c( 'db' )->db_last_insert_id();
    }

    function user_create_character( $user_name = FALSE ) {
        if ( FALSE == $this->ag->user ) {
            return FALSE;
        }

        if ( isset( $_GET[ 'char_name' ] ) ) {
            $user_name = $_GET[ 'char_name' ];
        }

        if ( FALSE == $user_name ) {
            return FALSE;
        }

    //todo: nonce
    //todo: max char count/user

        $character_id = $this->add_character(
            $this->ag->user[ 'id' ], $user_name );

        $this->ag->do_action( 'create_character',
                   $args = array( 'character_id' => $character_id ) );

        return TRUE;
    }

    function user_select_character( $id = FALSE ) {
        if ( FALSE == $this->ag->user ) {
            return FALSE;
        }

        if ( isset( $_GET[ 'id' ] ) ) {
            $id = $_GET[ 'id' ];
        }

        if ( FALSE == $id ) {
            return FALSE;
        }

        $character = $this->get_character_by_id( $id );

        if ( ( FALSE == $character ) ||
             ( $character[ 'user_id' ] != $this->ag->user[ 'id' ] ) ) {
            return FALSE;
        }

        $character[ 'meta' ] = $this->get_character_meta( $character[ 'id' ] );
        $this->ag->char = $character;

        $_SESSION[ 'c' ] = $character[ 'id' ];

        $this->ag->do_action( 'select_character' );

        return TRUE;
    }

    function user_clear_character() {
        unset( $_SESSION[ 'c' ] );
    }

    function game_character_active() {
        if ( isset( $_SESSION[ 'c' ] ) ) {
            $character = $this->get_character_by_id( $_SESSION[ 'c' ] );
            return $character;
        }
        return FALSE;
    }

    function get_character_meta( $id, $type = FALSE ) {
        if ( FALSE == $type ) {
            $meta_obj = $this->ag->c( 'db' )->db_fetch_all(
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
            $meta_obj = $this->ag->c( 'db' )->db_fetch_all(
                'SELECT * FROM character_meta ' .
                    'WHERE character_id=? AND key_type=?',
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
        $this->ag->c( 'db' )->db_execute(
            'INSERT INTO character_meta ( ' .
                'character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, ?, ?, ? )',
            array( $character_id, $key_type, $meta_key, $meta_value ) );

        if ( ( isset( $this->ag->char[ 'id' ] ) ) &&
             ( $this->ag->char[ 'id' ] == $character_id ) ) {

            if ( ! isset( $this->ag->char[ 'meta' ] ) ) {
                $this->ag->char[ 'meta' ] = array();
            }
            if ( ! isset( $this->ag->char[ 'meta' ][ $key_type ] ) ) {
                $this->ag->char[ 'meta' ][ $key_type ] = array();
            }

            $this->ag->char[ 'meta' ][ $key_type ][ $meta_key ] = $meta_value;
        }
    }

    function update_character_meta( $character_id, $key_type,
                                    $meta_key, $meta_value ) {
        $this->ag->c( 'db' )->db_execute(
            'UPDATE character_meta SET meta_value=? WHERE
                character_id=? AND key_type=? AND meta_key=?',
            array( $meta_value, $character_id, $key_type, $meta_key ) );

        if ( ( isset( $this->ag->char[ 'meta' ] ) ) &&
             ( isset( $this->ag->char[ 'meta' ][ $key_type ] ) ) ) {
            $this->ag->char[ 'meta' ][ $key_type ][ $meta_key ] = $meta_value;
        }
    }

    function clear_all_character_meta( $character_id ) {
        $this->ag->c( 'db' )->db_execute(
            'DELETE FROM character_meta WHERE character_id=?',
            array( $character_id ) );
    }

    function ensure_character_meta( $character_id, $key_type, $meta_key ) {
        $obj = $this->ag->c( 'db' )->db_fetch(
            'SELECT * FROM character_meta WHERE ' .
                'character_id=? AND key_type=? AND meta_key=?',
            array( $character_id, $key_type, $meta_key ) );
        if ( FALSE == $obj ) {
            $this->add_character_meta(
                $character_id, $key_type, $meta_key, '' );
        }
    }

    function ensure_character_meta_keygroup( $character_id, $key_type,
                                             $default_value, $meta_key_obj ) {
        $place_holders = implode(
            ',', array_fill( 0, count( $meta_key_obj ), '?' ) );
        $args = array_merge(
            array( $character_id, $key_type ), $meta_key_obj );

        $obj = $this->ag->c( 'db' )->db_fetch_all(
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
            $this->add_character_meta(
                $character_id, $key_type, $meta_key, $default_value );
        }
    }

    function character_meta( $key_type, $meta_key ) {
        if ( ! isset( $this->ag->char[ 'meta' ] ) ) {
            $this->ag->char[ 'meta' ] = $this->get_character_meta(
                $this->ag->char[ 'id' ] );
        }

        if ( ( ! isset( $this->ag->char[ 'meta' ][ $key_type ] ) ) &&
             ( defined( $key_type ) ) ) {
            $key_type = constant( $key_type );
        }

        if ( isset( $this->ag->char[ 'meta' ][ $key_type ] ) &&
             isset( $this->ag->char[ 'meta' ][ $key_type ][ $meta_key ] ) ) {
            return $this->ag->char[ 'meta' ][ $key_type ][ $meta_key ];
        }

        return '';
    }

    function character_meta_int( $key_type, $meta_key ) {
        return intval( character_meta( $key_type, $meta_key ) );
    }

    function character_meta_float( $key_type, $meta_key ) {
        return floatval( character_meta( $key_type, $meta_key ) );
    }

}
