<?php

class ArcadiaAchievement extends ArcadiaComponent {

    public $ag;

    function __construct( $ag_obj = FALSE, $key_type = 200 ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_achievement( $id ) {
        return $this->ag->c( 'db' )->fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_achievements() {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

    public function get_achievements( $character_id ) {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT a.meta_key AS id, a.meta_value AS meta_value, ' .
                'c.meta_value AS timestamp ' .
                'FROM game_meta AS a, character_meta AS c ' .
                'WHERE a.key_type=? AND c.key_type=? AND ' .
                'a.meta_key=c.meta_key AND c.character_id=? ' .
                'ORDER BY a.meta_key',
            array( $this->flag_game_meta,
                   $this->flag_character_meta,
                   $character_id ),
            'id'
        );
    }

    function award_achievement( $achievement_id ) {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        if ( isset( $this->ag->char[ 'meta' ][ $this->flag_game_meta ][
                        $achievement_id ] ) ) {
            return FALSE;
        }

        $this->ag->c( 'user' )->add_character_meta(
            $this->ag->char[ 'id' ], $this->flag_game_meta,
            $achievement_id, time() );

        $this->ag->do_action( 'award_achievement',
            array( 'achievement_id' => $achievement_id ) );

        return TRUE;
    }

}
