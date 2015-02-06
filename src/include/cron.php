<?php

class ArcadiaCron extends ArcadiaComponent {

    public $ag;

    function __construct( $ag_obj = FALSE, $key_type = 1000 ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_crons() {
        return $this->ag->c( 'db' )->fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            'meta_key' );
    }

    public function do_crons() {
        $cron_obj = $this->get_crons();

        foreach ( $cron_obj as $cron ) {
        }

        // todo: process crons, return FALSE if something goes wrong
        return TRUE;
    }

}
