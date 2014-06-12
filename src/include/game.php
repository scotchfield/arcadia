<?php

class Game {
    function Game() {
        $this->set_action( '' );
    }

    function get_action() {
        return $this->action;
    }
    function set_action( $action ) {
        $this->action = $action;
    }

    function ensure_meta() {
        if ( isset( $this->meta ) ) {
            return;
        }

        $meta_obj = db_fetch_all( 'SELECT * FROM game_meta', array() );

        $obj = array();
        foreach ( $meta_obj as $meta ) {
            if ( ! isset( $obj[ $meta[ 'key_type' ] ] ) ) {
                $obj[ $meta[ 'key_type' ] ] = array();
            }
            $obj[ $meta[ 'key_type' ] ][ $meta[ 'meta_key' ] ] =
                $meta[ 'meta_value' ];
        }

        $this->meta = $obj;
    }
}