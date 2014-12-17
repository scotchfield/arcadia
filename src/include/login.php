<?php

class ArcadiaLogin extends ArcadiaComponent {

    const NOTIFY_NO_USERNAME = 1;
    const NOTIFY_NO_PASSWORD = 2;
    const NOTIFY_NO_EMAIL = 3;
    const NOTIFY_USERNAME_EXISTS = 4;
    const NOTIFY_EMAIL_EXISTS = 5;
    const NOTIFY_BAD_USERPASS = 6;

    const NOTIFY_VALIDATE_NEEDED = 100;
    const NOTIFY_ALREADY_VALIDATED = 101;
    const NOTIFY_VALIDATE_SUCCESS = 102;

    function __construct() {
// TODO THIS IS A GAME-SETTING OPERATION, NOT INDEX
/*        add_state( 'do_page_content', array( $this, 'content_login' ) );
        add_state( 'do_page_content', array( $this, 'content_register' ) );
        add_state( 'do_page_content', array( $this, 'content_activate' ) );*/
    }

    public function content_login( $args = FALSE ) {
        global $ag;

        if ( strcmp( 'login', $ag->get_state() ) ) {
            return FALSE;
        }

        $user = $ag->c( 'user' )->get_user_by_name( $ag->get_arg( 'user' ) );
        if ( FALSE == $user ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_BAD_USERPASS );

            return FALSE;
        }

        if ( ! password_verify( $ag->get_arg( 'pass' ),
                                $user[ 'user_pass' ] ) ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_BAD_USERPASS );

            return FALSE;
        }

        $ag->user = $user;
        $_SESSION[ 'u' ] = $user[ 'id' ];

        $ag->set_redirect_header( GAME_URL );

        return TRUE;
    }

    public function content_register( $args = FALSE ) {
        global $ag;

        if ( strcmp( 'register', $ag->get_state() ) ) {
            return FALSE;
        }

        if ( ! $ag->get_arg( 'user' ) ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_NO_USERNAME );

            return FALSE;
        }

        if ( ! $ag->get_arg( 'pass' ) ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_NO_PASSWORD );

            return FALSE;
        }

        if ( ! $ag->get_arg( 'email' ) ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_NO_EMAIL );

            return FALSE;
        }

        $user = $ag->c( 'user' )->get_user_by_name( $ag->get_arg( 'user' ) );
        if ( FALSE != $user ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_USERNAME_EXISTS );

            return FALSE;
        }

        $user = $ag->c( 'user' )->get_user_by_email( $ag->get_arg( 'email' ) );
        if ( FALSE != $user ) {
            $ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_EMAIL_EXISTS );

            return FALSE;
        }

        $pass = password_hash(
            $ag->get_arg( 'pass' ), PASSWORD_DEFAULT );

        $args_send_email = TRUE;
        if ( isset( $args[ 'send_email' ] ) ) {
            $args_send_email = $args[ 'send_email' ];
        }

        $ag->c( 'user' )->add_user( $ag->get_arg( 'user' ), $pass,
            $ag->get_arg( 'email' ), $send_email = $args_send_email );

        $ag->set_redirect_header( GAME_URL . '?notify=' .
            self::NOTIFY_VALIDATE_NEEDED );

        return TRUE;
    }

    public function content_activate( $args = FALSE ) {
        global $ag;

        if ( strcmp( 'activate', $ag->get_state() ) ) {
            return FALSE;
        }

        if ( ! $ag->get_arg( 'user' ) ) {
            $ag->set_redirect_header( GAME_URL );

            return FALSE;
        }

        if ( ! $ag->get_arg( 'activate' ) ) {
            $ag->set_redirect_header( GAME_URL );

            return FALSE;
        }

        $user = $ag->c( 'user' )->get_user_by_id( $ag->get_arg( 'user' ) );

        if ( ! strcmp( $ag->get_arg( 'activate' ),
                       $user[ 'activation' ] ) ) {
            if ( $ag->c( 'user' )->is_user_active( $user ) ) {
                $ag->set_redirect_header( GAME_URL . '?notify=' .
                    self::NOTIFY_ALREADY_VALIDATED );

                return FALSE;
            } else {
                $ag->c( 'user' )->set_user_status( $user[ 'id' ],
                    $ag->c( 'common' )->set_bit(
                        $user[ 'status' ], ArcadiaUser::USER_STATUS_ACTIVE ) );

                $ag->do_action( 'validate_user',
                    $args = array( 'user_id' => $user[ 'id' ] ) );

                $ag->set_redirect_header( GAME_URL . '?notify='     .
                    self::NOTIFY_VALIDATE_SUCCESS );

                return TRUE;
            }
        }

        return FALSE;
    }

    public function content_logout( $args = FALSE ) {
        global $ag;

        if ( strcmp( 'logout', $ag->get_state() ) ) {
            return FALSE;
        }

        $ag->set_redirect_header( GAME_URL );

        $_SESSION = array();
        session_destroy();

        return TRUE;
    }

}
