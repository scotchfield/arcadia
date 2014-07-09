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


if ( ! isset( $_POST[ 'action' ] ) ) {
    header( 'Location: ' . GAME_URL );
    exit;
}

switch ( $_POST[ 'action' ] ) {
    case 'login':
        $user = get_user_by_name( $_POST[ 'user' ] );
        if ( FALSE == $user ) {
            // User doesn't exist.
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_BAD_USERPASS );
            exit;
        }

        if ( ! password_verify( $_POST[ 'pass' ], $user[ 'user_pass' ] ) ) {
            // Password doesn't match
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_BAD_USERPASS );
            exit;
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

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

        $user = get_user_by_name( $_POST[ 'user' ] );
        if ( FALSE != $user ) {
            // User name exists.
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_USERNAME_EXISTS );
            exit;
        }

        $user = get_user_by_email( $_POST[ 'email' ] );
        if ( FALSE != $user ) {
            // User name exists.
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_EMAIL_EXISTS );
            exit;
        }

        $pass = password_hash( $_POST[ 'pass' ], PASSWORD_DEFAULT );
        add_user( $_POST[ 'user' ], $pass, $_POST[ 'email' ] );

        header( 'Location: ' . GAME_URL . '?notify=' .
                GAME_LOGIN_NOTIFY_VALIDATE_NEEDED );
        // TODO: Add proper validate code: email, and set validate key.

        break;
}
