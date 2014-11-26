<?php

class ArcadiaQuest extends ArcadiaComponent {

    function __construct() {
        $this->flag_game_meta = 204;
        $this->flag_character_meta = 204;
    }

    public function get_quest( $id ) {
        return db_fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_quests() {
        return db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

}
