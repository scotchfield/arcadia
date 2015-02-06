<?php

class ArcadiaInventory extends ArcadiaComponent {

    public $ag;

    function __construct( $ag_obj = FALSE, $key_type = 209 ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_inventory( $character_id ) {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM character_meta WHERE key_type=? ORDER BY meta_key',
            array( $this->flag_character_meta ),
            'meta_key' );
    }

    public function award_item( $character_id, $item, $inventory = FALSE ) {
        if ( ! $inventory ) {
            $inventory = $this->get_inventory( $character_id );
        }

        $i = 0;
        while ( isset( $inventory[ $i ] ) ) {
            $i++;
        }

        return $this->ag->c( 'db' )->execute( 'INSERT INTO character_meta ' .
            '( character_id, key_type, meta_key, meta_value ) VALUES ' .
            '( ?, ?, ?, ? )',
            array( $character_id, $this->flag_character_meta, $i,
                   $item ) );
    }

    public function remove_item( $character_id, $meta_key ) {
        return $this->ag->c( 'db' )->execute(
            'DELETE FROM character_meta WHERE ' .
                'character_id = ? AND key_type = ? AND meta_key = ?',
            array( $character_id, $this->flag_character_meta, $meta_key ) );
    }

}
