<?php

class ArcadiaCron extends ArcadiaComponent {

    function __construct() {
        $this->flag_game_meta = 1000;
        $this->flag_character_meta = 1000;
    }

    public function get_crons() {
        return db_fetch_all(
            'SELECT * FROM game_meta WHERE key_type=?',
            array( $this->flag_game_meta ),
            'meta_key' );
    }

    public function do_crons() {
        $cron_obj = $this->get_crons();

        foreach ( $cron_obj as $cron ) {
            // debug_print( $cron );
        }

        // todo: process crons, return FALSE if something goes wrong
        return TRUE;
    }

}
