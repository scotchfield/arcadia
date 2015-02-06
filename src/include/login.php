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

    public $ag;

    function __construct( $ag_obj = FALSE ) {
// TODO THIS IS A GAME-SETTING OPERATION, NOT INDEX
/*        add_state( 'do_page_content', array( $this, 'content_login' ) );

        add_state( 'do_page_content', array( $this, 'content_register' ) );
        add_state( 'do_page_content', array( $this, 'content_activate' ) );*/

        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }
    }

    public function content_login( $args = FALSE ) {
        if ( strcmp( 'login', $this->ag->get_state() ) ) {
            return FALSE;
        }

        $user = $this->ag->c( 'user' )->get_user_by_name(
            $this->ag->get_arg( 'user' ) );
        if ( FALSE == $user ) {
            $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_BAD_USERPASS );

            return FALSE;
        }

        if ( ! password_verify( $this->ag->get_arg( 'pass' ),
                                $user[ 'user_pass' ] ) ) {
            $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_BAD_USERPASS );

            return FALSE;
        }

        $this->ag->user = $user;
        $_SESSION[ 'u' ] = $user[ 'id' ];

        $this->ag->set_redirect_header( GAME_URL );

        return TRUE;
    }

    public function content_register( $args = FALSE ) {
        if ( strcmp( 'register', $this->ag->get_state() ) ) {
            return FALSE;
        }

        if ( ! $this->ag->get_arg( 'user' ) ) {
            $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_NO_USERNAME );

            return FALSE;
        }

        if ( ! $this->ag->get_arg( 'pass' ) ) {
            $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_NO_PASSWORD );

            return FALSE;
        }

        if ( ! $this->ag->get_arg( 'email' ) ) {
            $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_NO_EMAIL );

            return FALSE;
        }

        $user = $this->ag->c( 'user' )->get_user_by_name(
            $this->ag->get_arg( 'user' ) );
        if ( FALSE != $user ) {
            $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_USERNAME_EXISTS );

            return FALSE;
        }

        $user = $this->ag->c( 'user' )->get_user_by_email(
            $this->ag->get_arg( 'email' ) );
        if ( FALSE != $user ) {
            $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                self::NOTIFY_EMAIL_EXISTS );

            return FALSE;
        }

        $pass = password_hash(
            $this->ag->get_arg( 'pass' ), PASSWORD_DEFAULT );

        $args_send_email = TRUE;
        if ( isset( $args[ 'send_email' ] ) ) {
            $args_send_email = $args[ 'send_email' ];
        }

        $this->ag->c( 'user' )->add_user( $this->ag->get_arg( 'user' ), $pass,
            $this->ag->get_arg( 'email' ), $send_email = $args_send_email );

        $this->ag->set_redirect_header( GAME_URL . '?notify=' .
            self::NOTIFY_VALIDATE_NEEDED );

        return TRUE;
    }

    public function content_activate( $args = FALSE ) {
        if ( strcmp( 'activate', $this->ag->get_state() ) ) {
            return FALSE;
        }

        if ( ! $this->ag->get_arg( 'user' ) ) {
            $this->ag->set_redirect_header( GAME_URL );

            return FALSE;
        }

        if ( ! $this->ag->get_arg( 'activate' ) ) {
            $this->ag->set_redirect_header( GAME_URL );

            return FALSE;
        }

        $user = $this->ag->c( 'user' )->get_user_by_id(
            $this->ag->get_arg( 'user' ) );

        if ( ! strcmp( $this->ag->get_arg( 'activate' ),
                       $user[ 'activation' ] ) ) {
            if ( $this->ag->c( 'user' )->is_user_active( $user ) ) {
                $this->ag->set_redirect_header( GAME_URL . '?notify=' .
                    self::NOTIFY_ALREADY_VALIDATED );

                return FALSE;
            } else {
                $this->ag->c( 'user' )->set_user_status( $user[ 'id' ],
                    $this->ag->c( 'common' )->set_bit(
                        $user[ 'status' ], ArcadiaUser::USER_STATUS_ACTIVE ) );

                $this->ag->do_action( 'validate_user',
                    $args = array( 'user_id' => $user[ 'id' ] ) );

                $this->ag->set_redirect_header( GAME_URL . '?notify='     .
                    self::NOTIFY_VALIDATE_SUCCESS );

                return TRUE;
            }
        }

        return FALSE;
    }

    public function content_logout( $args = FALSE ) {
        if ( strcmp( 'logout', $this->ag->get_state() ) ) {
            return FALSE;
        }

        $this->ag->set_redirect_header( GAME_URL );

        $_SESSION = array();
        session_destroy();

        return TRUE;
    }

}
