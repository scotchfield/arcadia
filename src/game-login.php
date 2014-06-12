<?php

require( dirname( __FILE__ ) . '/game-config.php' );
require( GAME_PATH . 'game-load.php' );

if ( ! isset( $_POST[ 'action' ] ) ) {
    header( 'Location: ' . GAME_URL );
    exit;
}

switch ( $_POST[ 'action' ] ) {
    case 'login':
        $user = get_user_by_name( $_POST[ 'user' ] );
        if ( FALSE == $user ) {
            // User doesn't exist.
            header( 'Location: ' . GAME_URL . '?err=5' );
            exit;
        }

        if ( ! password_verify( $_POST[ 'pass' ], $user[ 'user_pass' ] ) ) {
            // Password doesn't match
            header( 'Location: ' . GAME_URL . '?err=6' );
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
            header( 'Location: ' . GAME_URL . '?err=1' );
            exit;
        }
        if ( ! isset( $_POST[ 'pass' ] ) ) {
            // Password is not set.
            header( 'Location: ' . GAME_URL . '?err=2' );
            exit;
        }
        if ( ! isset( $_POST[ 'email' ] ) ) {
            // Email is not set.
            header( 'Location: ' . GAME_URL . '?err=3' );
            exit;
        }

        $user = get_user_by_name( $_POST[ 'user' ] );
        if ( FALSE != $user ) {
            // User name exists.
            header( 'Location: ' . GAME_URL . '?err=4' );
            exit;
        }

        $pass = password_hash( $_POST[ 'pass' ], PASSWORD_DEFAULT );
        add_user( $_POST[ 'user' ], $pass, $_POST[ 'email' ] );
        echo( 'Okay!  Sending you an email.  Gotta validate.' );

        break;
}
