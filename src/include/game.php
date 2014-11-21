<?php

class Arcadia_Game {
    private $game_state = '';

    private $components = array();

    public function get_state() {
        return $this->game_state;
    }

    public function set_state( $state ) {
        $this->game_state = $state;
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
