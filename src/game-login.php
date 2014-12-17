<?php

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

define( 'GAME_LOGIN_NOTIFY_NO_USERNAME',         1 );
define( 'GAME_LOGIN_NOTIFY_NO_PASSWORD',         2 );
define( 'GAME_LOGIN_NOTIFY_NO_EMAIL',            3 );
define( 'GAME_LOGIN_NOTIFY_USERNAME_EXISTS',     4 );
define( 'GAME_LOGIN_NOTIFY_EMAIL_EXISTS',        5 );
define( 'GAME_LOGIN_NOTIFY_BAD_USERPASS',        6 );

define( 'GAME_LOGIN_NOTIFY_VALIDATE_NEEDED',   100 );
define( 'GAME_LOGIN_NOTIFY_ALREADY_VALIDATED', 101 );
define( 'GAME_LOGIN_NOTIFY_VALIDATE_SUCCESS',  102 );


if ( isset( $_POST[ 'state' ] ) ) {
    switch ( $_POST[ 'state' ] ) {
        case 'login':
            $user = $ag->c( 'user' )->get_user_by_name( $_POST[ 'user' ] );
            if ( FALSE == $user ) {
                // User doesn't exist.
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_BAD_USERPASS );
                exit;
            }

            if ( ! password_verify( $_POST[ 'pass' ],
                                    $user[ 'user_pass' ] ) ) {
                // Password doesn't match
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_BAD_USERPASS );
                exit;
            }

            if ( session_status() == PHP_SESSION_NONE ) {
                session_start();
            }

            $GLOBALS[ 'ag' ]->user = $user;
            $_SESSION[ 'u' ] = $user[ 'id' ];

            header( 'Location: ' . GAME_URL );
            exit;

            break;

        case 'register':
            if ( ! isset( $_POST[ 'user' ] ) ) {
                // User name is not set.
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_NO_USERNAME );
                exit;
            }
            if ( ! isset( $_POST[ 'pass' ] ) ) {
                // Password is not set.
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_NO_PASSWORD );
                exit;
            }
            if ( ! isset( $_POST[ 'email' ] ) ) {
                // Email is not set.
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_NO_EMAIL );
                exit;
            }

            $user = $ag->c( 'user' )->get_user_by_name( $_POST[ 'user' ] );
            if ( FALSE != $user ) {
                // User name exists.
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_USERNAME_EXISTS );
                exit;
            }

            $user = $ag->c( 'user' )->get_user_by_email( $_POST[ 'email' ] );
            if ( FALSE != $user ) {
                // User email exists.
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_EMAIL_EXISTS );
                exit;
            }

            $pass = password_hash( $_POST[ 'pass' ], PASSWORD_DEFAULT );
            $ag->c( 'user' )->add_user(
                $_POST[ 'user' ], $pass, $_POST[ 'email' ] );

            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_VALIDATE_NEEDED );

            break;
    }

} else if ( isset( $_GET[ 'activate' ] ) ) {

    if ( ! isset( $_GET[ 'user' ] ) ) {
        header( 'Location: ' . GAME_URL );
        exit;
    }

    $user = $ag->c( 'user' )->get_user_by_id( $_GET[ 'user' ] );

    if ( ! strcmp( $_GET[ 'activate' ], $user[ 'activation' ] ) ) {
        if ( $ag->c( 'user' )->is_user_active( $user ) ) {
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_ALREADY_VALIDATED );
            exit;
        } else {
            $ag->c( 'user' )->set_user_status( $user[ 'id' ],
                $ag->c( 'common' )->set_bit(
                    $user[ 'status' ], ArcadiaUser::USER_STATUS_ACTIVE ) );

            $ag->do_action( 'validate_user',
                $args = array( 'user_id' => $user[ 'id' ] ) );

            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_VALIDATE_SUCCESS );
            exit;
        }
    }

}

header( 'Location: ' . GAME_URL );
