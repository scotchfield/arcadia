<?php

class ArcadiaBuff extends ArcadiaComponent {

    function __construct() {
        $this->flag_game_meta = 202;
        $this->flag_character_meta = 202;
    }

    public function get_buff( $id ) {
        return db_fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_buffs() {
        return db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=? ORDER BY meta_key',
            array( $this->flag_game_meta ),
            'meta_key' );
    }

    public function get_buffs( $character_id ) {
        return db_fetch_all(
            'SELECT * FROM game_meta AS a, character_meta AS c ' .
                'WHERE a.key_type=? AND c.key_type=? AND ' .
                'a.meta_key=c.meta_key AND c.character_id=? ORDER BY a.meta_key',
            array( $this->flag_game_meta, $this->flag_character_meta,
                   $character_id ),
            'meta_key'
        );
    }

    public function award_buff( $buff_id, $duration ) {
        global $character;

        if ( FALSE == $character ) {
            return FALSE;
        }

        ensure_character_meta( $character[ 'id' ], $this->flag_game_meta,
            $buff_id );
        update_character_meta( $character[ 'id' ], $this->flag_game_meta,
            $buff_id, time() + $duration );

        do_state( 'award_buff',
                  array( 'buff_id' => $buff_id ) );

        return TRUE;
    }

    public function check_buff( $buff_id ) {
        global $character;

        if ( FALSE == $character ) {
            return FALSE;
        }

        if ( ! isset( $character[ 'meta' ][ $this->flag_character_meta ] ) ) {
            return FALSE;
        }

        if ( ! isset( $character[ 'meta' ][ $this->flag_character_meta
                          ][ $buff_id ] ) ) {
            return FALSE;
        }

        $t = intval( $character[ 'meta' ][ $this->flag_character_meta
                         ][ $buff_id ] ) - time();

        if ( $t <= 0 ) {
            return FALSE;
        }

        return $t;
    }

    public function remove_buff( $buff_id ) {
        global $character;

        unset( $character[ 'meta' ][ $this->flag_character_meta ][ $buff_id ] );

        db_execute(
            'DELETE FROM character_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_character_meta, $buff_id ) );
    }

    public function update_buffs() {
        global $character;

        if ( FALSE == $character ) {
            return;
        }

        if ( ! isset( $character[ 'meta' ][ $this->flag_character_meta ] ) ) {
            return;
        }

        foreach ( $character[ 'meta' ][ $this->flag_character_meta ] as
                      $buff_id => $buff_expire ) {
            if ( intval( $buff_expire ) <= time() ) {
                remove_buff( $buff_id );
            }
        }

        do_state( 'apply_buffs' );
    }

}