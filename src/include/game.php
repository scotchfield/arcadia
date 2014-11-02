<?php

class Arcadia_Game {
    private $game_action = '';

    public function get_action() {
        return $this->game_action;
    }

    public function set_action( $action ) {
        $this->game_action = $action;
    }
}

$GLOBALS[ 'game' ] = new Arcadia_Game();
