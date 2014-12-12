<?php

class ArcadiaNpc extends ArcadiaComponent {

    function __construct( $key_type = 208 ) {
        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_npc( $id ) {
        global $ag;

        return $ag->c( 'db' )->db_fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_npcs() {
        global $ag;

        return $ag->c( 'db' )->db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

}
