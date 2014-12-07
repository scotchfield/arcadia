<?php

class ArcadiaLogin extends ArcadiaComponent {

    const NOTIFY_NO_USERNAME = 1;
    const NOTIFY_BAD_USERPASS = 6;

    function __construct() {
        add_state( 'do_page_content', array( $this, 'content_login' ) );
        add_state( 'do_page_content', array( $this, 'content_register' ) );
        add_state( 'do_page_content', array( $this, 'content_activate' ) );
    }

    public function content_login( $args = FALSE ) {
        global $ag;

        if ( strcmp( 'login', $ag->get_state() ) ) {
            return FALSE;
        }

        $user = get_user_by_name( $ag->get_state_arg( 'user' ) );
        if ( FALSE == $user ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_BAD_USERPASS );

            return FALSE;
        }

        if ( ! password_verify( $ag->get_state_arg( 'pass' ),
                                $user[ 'user_pass' ] ) ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_BAD_USERPASS );

            return FALSE;
        }

        if ( session_status() == PHP_SESSION_NONE ) {
            session_start();
        }

        $ag->user = $user;
        $_SESSION[ 'u' ] = $user[ 'id' ];

        $ag->set_redirect_header( GAME_URL );

//TODO THIS IS A GAME-SETTING OPERATION, NOT INDEX

        return TRUE;
    }

    public function content_register( $args ) {
        global $ag;

        if ( strcmp( 'register', $ag->get_state() ) ) {
            return;
        }

        if ( ! $ag->get_state_arg( 'user' ) ) {
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_NO_USERNAME );
            exit;
        }
        if ( ! isset( $_POST[ 'pass' ] ) ) {
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_NO_PASSWORD );
            exit;
        }
        if ( ! isset( $_POST[ 'email' ] ) ) {
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_NO_EMAIL );
            exit;
        }

        $user = get_user_by_name( $_POST[ 'user' ] );
        if ( FALSE != $user ) {
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_USERNAME_EXISTS );
            exit;
        }

        $user = get_user_by_email( $_POST[ 'email' ] );
        if ( FALSE != $user ) {
            header( 'Location: ' . GAME_URL . '?notify=' .
                    GAME_LOGIN_NOTIFY_EMAIL_EXISTS );
            exit;
        }

        $pass = password_hash( $_POST[ 'pass' ], PASSWORD_DEFAULT );
        add_user( $_POST[ 'user' ], $pass, $_POST[ 'email' ] );

        header( 'Location: ' . GAME_URL . '?notify=' .
                GAME_LOGIN_NOTIFY_VALIDATE_NEEDED );
    }

    public function content_activate( $args ) {
        global $ag;

        if ( strcmp( 'activate', $ag->get_state() ) ) {
            return;
        }

        if ( ! isset( $_GET[ 'user' ] ) ) {
            header( 'Location: ' . GAME_URL );
            exit;
        }

        $user = get_user_by_id( $_GET[ 'user' ] );

        if ( ! strcmp( $_GET[ 'activate' ], $user[ 'activation' ] ) ) {
            if ( is_user_active( $user ) ) {
                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_ALREADY_VALIDATED );
                exit;
            } else {
                set_user_status( $user[ 'id' ],
                    set_bit( $user[ 'status' ], game_user_status_active ) );

                do_state( 'validate_user',
                    $args = array( 'user_id' => $user[ 'id' ] ) );

                header( 'Location: ' . GAME_URL . '?notify=' .
                        GAME_LOGIN_NOTIFY_VALIDATE_SUCCESS );
                exit;
            }
        }
    }

}
