<?php

class ArcadiaHeartbeat extends ArcadiaComponent {

    function __construct( $ag_obj = FALSE, $key_type = 206 ) {
        parent::__construct( $ag_obj );

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    function add_heartbeat( $meta_value = array() ) {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        $this->ag->c( 'db' )->execute( 'DELETE FROM character_meta ' .
            'WHERE character_id=? AND key_type=?',
            array( $this->ag->char[ 'id' ], $this->flag_character_meta ) );

        return $this->ag->c( 'db' )->execute( 'INSERT INTO character_meta ' .
            '( character_id, key_type, meta_key, meta_value ) VALUES ' .
            '( ?, ?, ?, ? )',
            array( $this->ag->char[ 'id' ], $this->flag_character_meta,
                   time(), json_encode( $meta_value, JSON_FORCE_OBJECT ) )
        );
    }

    function get_all_heartbeats() {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM character_meta WHERE key_type=?',
            array( $this->flag_character_meta )
        );
    }

    function get_heartbeat_characters( $time_delta ) {
        $time_value = time() - $time_delta;

        return $this->ag->c( 'db' )->fetch_all(
            'SELECT c.id, c.character_name, m.meta_key, m.meta_value ' .
                'FROM characters AS c, character_meta AS m ' .
                'WHERE c.id=m.character_id AND meta_key >= ? ' .
                'ORDER BY c.character_name ASC',
            array( $time_value ),
            $assoc = 'id'
        );
    }
}
