<?php

class ArcadiaLog {

    public $ag;

    public function __construct( $ag ) {
        $this->ag = $ag;
    }

    public function log_add( $log_type, $char_id, $meta_value ) {
        if ( ! $this->ag->c( 'db' ) ) {
            return FALSE;
        }

        return $this->ag->c( 'db' )->execute(
            'INSERT INTO logs ( log_type, char_id, timestamp, meta_value ) ' .
                'VALUES ( ?, ?, ?, ? )',
            array( $log_type, $char_id, time(), $meta_value ) );
    }

}