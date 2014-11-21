<?php

class ArcadiaItem extends ArcadiaComponent {

    function __construct() {
        $this->flag_game_meta = 201;
        $this->flag_character_meta = 201;
    }

    function get_item( $id ) {
        return db_fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    function get_all_items() {
        return db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=? ORDER BY meta_key',
            array( $this->flag_game_meta ),
            'meta_key' );
    }

    function get_items( $character_id ) {
        return db_fetch_all(
            'SELECT * FROM game_meta AS a, character_meta AS c ' .
                'WHERE a.key_type=? AND c.key_type=? AND ' .
                'a.meta_key=c.meta_key AND c.character_id=? ' .
                'ORDER BY a.meta_key',
            array( $this->flag_game_meta, $this->flag_character_meta,
                   $character_id ),
            'meta_key'
        );
    }

    function award_item( $meta_key, $meta_value ) {
        global $character;

        if ( FALSE == $character ) {
            return;
        }

        add_character_meta( $character[ 'id' ], $this->flag_game_meta,
            $meta_key, $meta_value );

        do_state( 'award_item',
                  array( 'item_id' => $meta_key ) );
    }

}
