<?php

class ArcadiaMail extends ArcadiaComponent {

    function __construct( $ag_obj = FALSE, $key_type = 205 ) {
        parent::__construct( $ag_obj );

        $this->flag_game_meta = $key_type;
        $this->flag_character_meta = $key_type;
    }

    public function get_mail( $id ) {
        return $this->ag->meta->get_game_meta( $this->flag_game_meta, $id );
    }

    public function get_mail_array( $id_array ) {
        return $this->ag->meta->get_game_meta_array(
            $this->flag_game_meta, $id_array );
    }

}
