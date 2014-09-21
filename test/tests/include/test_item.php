<?php

class TestArcadiaItem extends PHPUnit_Framework_TestCase {

    /**
     * @covers ::item_init
     */
    public function test_item_init() {
        item_init();

        $this->assertTrue( defined( 'game_meta_type_item' ) );
        $this->assertTrue( defined( 'game_character_meta_type_item' ) );
    }

}
