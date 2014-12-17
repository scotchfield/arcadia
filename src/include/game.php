<?php

class ArcadiaGame {
    private $game_state = '';
    private $game_states = array();
    private $game_args = array();

    private $components = array();

    private $logger = FALSE;
    private $redirect_header = FALSE;

    public $user = FALSE, $char = FALSE;

    public $meta;

    public function __construct() {
        $this->meta = new ArcadiaGameMeta();
    }

    public function get_state() {
        return $this->game_state;
    }

    public function set_state( $state ) {
        $this->game_state = $state;
    }

    public function get_arg( $k, $default = FALSE ) {
        if ( isset( $this->game_args[ $k ] ) ) {
            return $this->game_args[ $k ];
        }
        return $default;
    }

    public function set_arg( $k, $v ) {
        $this->game_args[ $k ] = $v;
    }

    public function clear_args() {
        $this->game_args = array();
    }

    public function set_component( $component_id, $component ) {
        $this->components[ $component_id ] = $component;
    }

    public function get_component( $component_id ) {
        if ( isset( $this->components[ $component_id ] ) ) {
            return $this->components[ $component_id ];
        }
        return FALSE;
    }

    public function c( $component_id ) {
        return $this->get_component( $component_id );
    }

    public function do_action( $action_id, $args = array() ) {
        foreach ( $this->game_states as $state ) {
            if ( $state[ 0 ] != $action_id ) {
                continue;
            }

            if ( $state[ 1 ] && ( $state[ 1 ] != $this->get_state() ) ) {
                continue;
            }

            if ( 0 == count( $args ) ) {
                $arg_obj = array( $state[ 3 ] );
            } else {
                $arg_obj = array( array_merge( $state[ 3 ], $args ) );
            }

            call_user_func_array( $state[ 2 ], $arg_obj );
        }
    }

    public function add_state( $state_id, $action_id, $function, $args = array() ) {
        $this->game_states[] = array( $state_id, $action_id, $function, $args );
    }

    public function add_state_priority( $state_id, $action_id, $function,
                                        $args = array() ) {
        array_unshift( $this->game_states,
                       array( $state_id, $action_id, $function, $args ) );
    }

    public function remove_state( $state_id, $action_id, $function ) {
        foreach ( $this->game_states as $k => $state ) {
            if ( ( $state[ 0 ] == $state_id ) &&
                 ( $state[ 1 ] == $action_id ) &&
                 ( $state[ 2 ] == $function ) ) {
                unset( $this->game_states[ $k ] );
            }
        }
    }

    public function state_exists( $state_id ) {
        foreach ( $this->game_states as $state ) {
            if ( $state_id == $state[ 0 ] ) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function char_meta( $key_type, $meta_key, $default ) {
        if ( ! $this->char ) {
            return FALSE;
        }

        if ( ! isset( $this->char[ 'meta' ][ $key_type ][ $meta_key ] ) ) {
            return $default;
        }

        return $this->char[ 'meta' ][ $key_type ][ $meta_key ];
    }

    public function set_redirect_header( $location ) {
        $this->redirect_header = $location;
    }

    public function redirect_header() {
        return $this->redirect_header;
    }

    public function debug_print( $x ) {
        if ( is_array( $x ) ) {
            echo '<p>';
            print_r( $x );
            echo '</p>';
        } else {
            echo '<p>' . $x . '</p>';
        }
    }

    public function debug_time() {
        list( $usec, $sec ) = explode( ' ', microtime() );
        return ( ( float ) $usec + ( float ) $sec );
    }

}
