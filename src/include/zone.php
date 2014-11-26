<?php

class ArcadiaZone extends ArcadiaComponent {

    function __construct() {
        $this->flag_game_meta = 203;
        $this->flag_character_meta = 203;
    }

    public function get_zone( $id ) {
        return db_fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_zones() {
        return db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

}
