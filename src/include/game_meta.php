<?php

/* game_meta.php
 * To implement features of the game that can be stored in game-meta,
 * including zones, items, achievements, and so on, Arcadia relies on
 * these functions. A feature defines its unique keys, calls these
 * functions as necessary, and if necessary, defines its own functions.
 */

function get_game_meta( $key_type, $meta_key ) {
    return db_fetch(
        'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
        array( $key_type, $meta_key ) );
}

function get_game_meta_by_key( $meta_key ) {
    return db_fetch_all(
        'SELECT * FROM game_meta WHERE key_type=?',
        array( $meta_key ) );
}

function get_character_game_meta( $character_id, $game_meta_key,
	 			  $character_meta_key ) {
    return db_fetch_all(
        'SELECT g.meta_key AS id, g.meta_value AS meta_value, ' .
            'c.meta_value AS timestamp ' .
            'FROM game_meta AS g, character_meta AS c ' .
            'WHERE g.key_type=? AND c.key_type=? AND ' .
            'g.meta_key=c.meta_key AND c.character_id=? ORDER BY g.meta_key',
        array( $game_meta_key, $character_meta_key, $character_id ),
        'id'
    );
}
