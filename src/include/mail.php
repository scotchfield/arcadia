<?php

class ArcadiaMail extends ArcadiaComponent {

    function __construct( $key_type = 205 ) {
        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_mail( $id ) {
        global $ag;

        return $ag->meta->get_game_meta( $this->flag_game_meta, $id );
    }

    public function get_mail_array( $id_array ) {
        global $ag;

        return $ag->meta->get_game_meta_array(
            $this->flag_game_meta, $id_array );
    }

}
