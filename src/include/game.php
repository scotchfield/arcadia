<?php

class Arcadia_Game {
    private $game_action = '';

    private $components = array();

    public function get_action() {
        return $this->game_action;
    }

    public function set_action( $action ) {
        $this->game_action = $action;
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
}

$GLOBALS[ 'game' ] = new Arcadia_Game();
