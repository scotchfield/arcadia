<?php

class TestArcadiaInventory extends PHPUnit_Framework_TestCase {

    /**
     * @covers ArcadiaInventory::__construct
     */
    public function test_inventory_get_flag_game_meta() {
        $component = new ArcadiaInventory();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

}
