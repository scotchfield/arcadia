<?php

class ArcadiaBuff extends ArcadiaComponent {

    function __construct( $ag_obj = FALSE, $key_type = 202 ) {
        parent::__construct( $ag_obj );

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_buff( $id ) {
        return $this->ag->c( 'db' )->fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_buffs() {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=? ORDER BY meta_key',
            array( $this->flag_game_meta ),
            'meta_key' );
    }

    public function get_buffs( $character_id ) {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta AS a, character_meta AS c ' .
                'WHERE a.key_type=? AND c.key_type=? AND ' .
                'a.meta_key=c.meta_key AND c.character_id=? ' .
                'ORDER BY a.meta_key',
            array( $this->flag_game_meta, $this->flag_character_meta,
                   $character_id ),
            'meta_key'
        );
    }

    public function award_buff( $buff_id, $duration ) {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        $this->ag->c( 'user' )->ensure_character_meta(
            $this->ag->char[ 'id' ], $this->flag_game_meta, $buff_id );
        $this->ag->c( 'user' )->update_character_meta(
            $this->ag->char[ 'id' ], $this->flag_game_meta,
            $buff_id, time() + $duration );

        $this->ag->do_action( 'award_buff',
                   array( 'buff_id' => $buff_id ) );

        return TRUE;
    }

    public function check_buff( $buff_id ) {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        if ( ! isset( $this->ag->char[ 'meta' ][ $this->flag_character_meta ] ) ) {
            return FALSE;
        }

        if ( ! isset( $this->ag->char[ 'meta' ][ $this->flag_character_meta
                          ][ $buff_id ] ) ) {
            return FALSE;
        }

        $t = intval( $this->ag->char[ 'meta' ][ $this->flag_character_meta
                         ][ $buff_id ] ) - time();

        if ( $t <= 0 ) {
            return FALSE;
        }

        return $t;
    }

    public function remove_buff( $buff_id ) {
        unset( $this->ag->char[ 'meta' ][ $this->flag_character_meta ][ $buff_id ] );

        $this->ag->c( 'db' )->execute(
            'DELETE FROM character_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_character_meta, $buff_id ) );
    }

    public function update_buffs() {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        if ( ! isset( $this->ag->char[ 'meta' ][ $this->flag_character_meta ] ) ) {
            return FALSE;
        }

        foreach ( $this->ag->char[ 'meta' ][ $this->flag_character_meta ] as
                      $buff_id => $buff_expire ) {
            if ( intval( $buff_expire ) <= time() ) {
                $this->remove_buff( $buff_id );
            }
        }

        $this->ag->do_action( 'apply_buffs' );

        return TRUE;
    }

}