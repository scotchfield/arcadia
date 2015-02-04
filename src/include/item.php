<?php

class ArcadiaItem extends ArcadiaComponent {

    public $ag;

    function __construct( $ag_obj = FALSE, $key_type = 201 ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    function get_item( $id ) {
        return $this->ag->c( 'db' )->fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    function get_all_items() {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=? ORDER BY meta_key',
            array( $this->flag_game_meta ),
            'meta_key' );
    }

    function get_item_list( $item_ids ) {
        $place_holders = implode(
            ',', array_fill( 0, count( $item_ids ), '?' ) );
        $args = array_merge( array( $this->flag_game_meta ), $item_ids );

        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=? AND ' .
                'meta_key IN (' . $place_holders . ')',
            $args,
            $key_assoc = 'meta_key' );
    }

    function get_items( $character_id ) {
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

    function award_item( $meta_key, $meta_value ) {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        $this->ag->c( 'user' )->add_character_meta(
            $this->ag->char[ 'id' ], $this->flag_game_meta, $meta_key, $meta_value );

        $this->ag->do_action( 'award_item',
                   array( 'item_id' => $meta_key ) );

        return TRUE;
    }

}
