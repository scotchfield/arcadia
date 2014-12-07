<?php

class ArcadiaGame {
    private $game_state = '';
    private $game_state_args = array();

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

    public function get_state_arg( $k, $default = FALSE ) {
        if ( isset( $this->game_state_args[ $k ] ) ) {
            return $this->game_state_args[ $k ];
        }
        return $default;
    }

    public function set_state_arg( $k, $v ) {
        $this->game_state_args[ $k ] = $v;
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

    public function set_logger( $logger ) {
        $this->logger = $logger;
    }

    public function log_add( $log_type, $char_id, $meta_value ) {
        if ( FALSE != $this->logger ) {
            $this->logger->log_add( $log_type, $char_id, $meta_value );
        }
    }

    public function char_meta( $key_type, $meta_key, $default ) {
        if ( ! $this->char ) {
            return $default;
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
}
