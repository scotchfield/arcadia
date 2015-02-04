<?php

/* game_meta.php
 * To implement features of the game that can be stored in game-meta,
 * including zones, items, achievements, and so on, Arcadia relies on
 * these functions. A feature defines its unique keys, calls these
 * functions as necessary, and if necessary, defines its own functions.
 */

class ArcadiaGameMeta {

    public $ag;

    function __construct( $ag_obj = FALSE ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }
    }

    function get_game_meta( $key_type, $meta_key ) {
        return $this->ag->c( 'db' )->fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $key_type, $meta_key ) );
    }

    function get_game_meta_array( $key_type, $meta_key_array ) {
        $place_holders = implode(
            ',', array_fill( 0, count( $meta_key_array ), '?' ) );
        $args = array_merge( array( $key_type ), $meta_key_array );

        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key IN (' .
                $place_holders . ')',
            $args,
            $key_assoc = 'meta_key'
        );
    }

    function get_game_meta_by_key( $key_type ) {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $key_type ),
            $key_assoc = 'meta_key' );
    }

    function get_character_game_meta( $character_id, $game_meta_key,
                                      $character_meta_key ) {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT g.meta_key AS id, g.meta_value AS meta_value, ' .
                'c.meta_value AS timestamp ' .
                'FROM game_meta AS g, character_meta AS c ' .
                'WHERE g.key_type=? AND c.key_type=? AND ' .
                'g.meta_key=c.meta_key AND c.character_id=? ' .
                'ORDER BY g.meta_key',
            array( $game_meta_key, $character_meta_key, $character_id ),
            'id'
        );
    }

    function get_game_meta_all() {
        $meta_obj = $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta', array() );

        $obj = array();
        foreach ( $meta_obj as $meta ) {
            if ( ! isset( $obj[ $meta[ 'key_type' ] ] ) ) {
                $obj[ $meta[ 'key_type' ] ] = array();
            }
            $obj[ $meta[ 'key_type' ] ][ $meta[ 'meta_key' ] ] =
                $meta[ 'meta_value' ];
        }

        return $obj;
    }

    function update_game_meta( $key_type, $meta_key, $meta_value ) {
        $this->ag->c( 'db' )->execute(
            'UPDATE game_meta SET meta_value=? ' .
                'WHERE key_type=? AND meta_key=?',
            array( $meta_value, $key_type, $meta_key ) );
    }

}
