<?php

class ArcadiaZone extends ArcadiaComponent {

    function __construct( $key_type = 203 ) {
        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_zone( $id ) {
        global $ag;

        return $ag->c( 'db' )->db_fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_zones() {
        global $ag;

        return $ag->c( 'db' )->db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

}
