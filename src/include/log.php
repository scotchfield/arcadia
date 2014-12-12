<?php

class ArcadiaLog {

    function log_add( $log_type, $char_id, $meta_value ) {
        global $ag;

        return $ag->c( 'db' )->db_execute(
            'INSERT INTO logs ( log_type, char_id, timestamp, meta_value ) ' .
                'VALUES ( ?, ?, ?, ? )',
            array( $log_type, $char_id, time(), $meta_value ) );
    }

}