<?php

class TestArcadiaBuff extends PHPUnit_Framework_TestCase {

    /**
     * @covers ArcadiaBuff::get_flag_game_meta
     */
    public function test_buff_get_flag_game_meta() {
        $component = new ArcadiaBuff();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaBuff::get_flag_character_meta
     */
    public function test_buff_get_flag_character_meta() {
        $component = new ArcadiaBuff();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

}
