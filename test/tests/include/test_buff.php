<?php

class TestArcadiaBuff extends PHPUnit_Framework_TestCase {

    /**
     * @covers ::buff_init
     */
    public function test_buff_init() {
        buff_init();

        $this->assertTrue( defined( 'game_meta_type_buff' ) );
        $this->assertTrue( defined( 'game_character_meta_type_buff' ) );
    }

}
