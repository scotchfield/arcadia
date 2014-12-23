<?php

class ArcadiaZone extends ArcadiaComponent {

    function __construct( $key_type = 203 ) {
        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_zone( $id ) {
        global $ag;

        return $ag->c( 'db' )->fetch(
            'SELECT * FROM game_meta WHERE key_type=? AND meta_key=?',
            array( $this->flag_game_meta, $id ) );
    }

    public function get_all_zones() {
        global $ag;

        return $ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            $assoc = 'meta_key' );
    }

    public function add_zone( $id, $meta_value ) {
        global $ag;

        return $ag->c( 'db' )->execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, ?, ? )',
            array( $this->flag_game_meta, $id, $meta_value ) );
    }

    public function update_zone( $id, $meta_value ) {
        global $ag;

        return $ag->c( 'db' )->execute(
            'UPDATE game_meta SET meta_value=? ' .
                'WHERE key_type=? AND meta_key=?',
            array( $meta_value, $this->flag_game_meta, $id ) );
    }

    public function modify_zone_key( $old_id, $new_id ) {
        global $ag;

        return $ag->c( 'db' )->execute(
            'UPDATE game_meta SET meta_key=? WHERE key_type=? AND meta_key=?',
            array( $new_id, $this->flag_game_meta, $old_id ) );
    }
}
