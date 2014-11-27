<?php

class ArcadiaTracking extends ArcadiaComponent {

    function __construct( $key_type = 207 ) {
        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_tracking( $character_id, $tracking_id ) {
        return db_fetch(
            'SELECT * FROM character_meta WHERE character_id=? ' .
                'AND key_type=? AND meta_key=?',
            array( $character_id, $this->flag_character_meta, $tracking_id ) );
    }

    public function set_tracking( $character_id, $tracking_id, $value ) {
        db_begin_transaction();

        db_execute(
            'DELETE FROM character_meta ' .
                'WHERE character_id=? AND key_type=? AND meta_key=?',
            array( $character_id, $this->flag_character_meta, $tracking_id ) );

        $result = db_execute(
            'INSERT INTO character_meta ' .
                '( character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, ?, ?, ? )',
            array( $character_id, $this->flag_character_meta,
                   $tracking_id, $value ) );

        db_commit();

        return $result;
    }

}
