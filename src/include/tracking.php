<?php

class ArcadiaTracking extends ArcadiaComponent {

    function __construct( $ag_obj = FALSE, $key_type = 207 ) {
        parent::__construct( $ag_obj );

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_tracking( $character_id, $tracking_id ) {
        return $this->ag->c( 'db' )->fetch(
            'SELECT * FROM character_meta WHERE character_id=? ' .
                'AND key_type=? AND meta_key=?',
            array( $character_id, $this->flag_character_meta, $tracking_id ) );
    }

    public function set_tracking( $character_id, $tracking_id, $value ) {
        $this->ag->c( 'db' )->begin_transaction();

        $this->ag->c( 'db' )->execute(
            'DELETE FROM character_meta ' .
                'WHERE character_id=? AND key_type=? AND meta_key=?',
            array( $character_id, $this->flag_character_meta, $tracking_id ) );

        $result = $this->ag->c( 'db' )->execute(
            'INSERT INTO character_meta ' .
                '( character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, ?, ?, ? )',
            array( $character_id, $this->flag_character_meta,
                   $tracking_id, $value ) );

        $this->ag->c( 'db' )->commit();

        return $result;
    }

}
