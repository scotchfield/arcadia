<?php

class ArcadiaNpc extends ArcadiaComponent {

    public $ag;

    function __construct( $ag_obj = FALSE, $key_type = 208 ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_npc( $id ) {
        return $this->ag->c( 'db' )->fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_npcs() {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

}
