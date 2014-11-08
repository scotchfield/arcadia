<?php

class ArcadiaAchievement extends ArcadiaComponent {

    function __construct() {
        $this->flag_game_meta = 200;
        $this->flag_character_meta = 200;
    }

    public function get_achievement( $id ) {
        return db_fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_achievements() {
        return db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

    public function get_achievements( $character_id ) {
        return db_fetch_all(
            'SELECT a.meta_key AS id, a.meta_value AS meta_value, ' .
                'c.meta_value AS timestamp ' .
                'FROM game_meta AS a, character_meta AS c ' .
                'WHERE a.key_type=? AND c.key_type=? AND ' .
                'a.meta_key=c.meta_key AND c.character_id=? ORDER BY a.meta_key',
            array( $this->flag_game_meta,
                   $this->flag_character_meta,
                   $character_id ),
            'id'
        );
    }

    function award_achievement( $achievement_id ) {
        global $character;

        if ( FALSE == $character ) {
            return FALSE;
        }

        if ( isset( $character[ 'meta' ][ $this->flag_game_meta ][
                        $achievement_id ] ) ) {
            return FALSE;
        }

        add_character_meta( $character[ 'id' ], $this->flag_game_meta,
            $achievement_id, time() );

        do_action( 'award_achievement',
                   array( 'achievement_id' => $achievement_id ) );

        return TRUE;
    }

}
