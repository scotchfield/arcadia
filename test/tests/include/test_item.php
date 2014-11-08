<?php

class TestArcadiaItem extends PHPUnit_Framework_TestCase {

    /**
     * @covers ArcadiaItem::get_flag_game_meta
     */
    public function test_buff_get_flag_game_meta() {
        $component = new ArcadiaItem();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaItem::get_flag_character_meta
     */
    public function test_buff_get_flag_character_meta() {
        $component = new ArcadiaItem();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

}
