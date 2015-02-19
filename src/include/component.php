<?php

class ArcadiaComponent {
    protected $flag_game_meta;
    protected $flag_character_meta;

    protected $ag;

    public function __construct( $ag_obj = FALSE ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }
    }

    public function get_flag_game_meta() {
        return $this->flag_game_meta;
    }

    public function get_flag_character_meta() {
        return $this->flag_character_meta;
    }
}
