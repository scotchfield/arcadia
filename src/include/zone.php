<?php

class ArcadiaZone extends ArcadiaComponent {

    public $ag;

    function __construct( $ag_obj = FALSE, $key_type = 203 ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_zone( $id ) {
        return $this->ag->c( 'db' )->fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_zones() {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

    public function add_zone( $id, $meta_value ) {
        return $this->ag->c( 'db' )->execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, ?, ? )',
            array( $this->flag_game_meta, $id, $meta_value ) );
    }

    public function update_zone( $id, $meta_value ) {
        return $this->ag->c( 'db' )->execute(
            'UPDATE game_meta SET meta_value=? ' .
                'WHERE key_type=? AND meta_key=?',
            array( $meta_value, $this->flag_game_meta, $id ) );
    }

    public function modify_zone_key( $old_id, $new_id ) {
        return $this->ag->c( 'db' )->execute(
            'UPDATE game_meta SET meta_key=? WHERE key_type=? AND meta_key=?',
            array( $new_id, $this->flag_game_meta, $old_id ) );
    }
}
