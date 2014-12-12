<?php

class TestArcadiaInventory extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $component = new ArcadiaInventory();

        do_action( 'post_load' );

        db_execute(
            'INSERT INTO character_meta ' .
                '( character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( 1, ?, 0, "test" )',
            array( $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM character_meta', array() );
    }

    /**
     * @covers ArcadiaInventory::__construct
     */
    public function test_inventory_get_flag_game_meta() {
        $component = new ArcadiaInventory();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaInventory::get_inventory
     */
    public function test_inventory_get_inventory_single() {
        $component = new ArcadiaInventory();

        $result = $component->get_inventory( 1 );

        $this->assertCount( 1, $result );
        $this->assertEquals( $result[ 0 ],
            array( 'character_id' => '1',
                   'key_type' => '209',
                   'meta_key' => '0',
                   'meta_value' => 'test' ) );
    }

    /**
     * @covers ArcadiaInventory::get_inventory
     */
    public function test_inventory_get_inventory_empty() {
        $component = new ArcadiaInventory();

        db_execute( 'DELETE FROM character_meta', array() );

        $result = $component->get_inventory( 1 );

        $this->assertCount( 0, $result );
    }

    /**
     * @covers ArcadiaInventory::award_item
     */
    public function test_inventory_award_item() {
        $component = new ArcadiaInventory();

        $result = $component->award_item( 1, 'test_new_item' );

        $this->assertTrue( $result );

        $result = $component->get_inventory( 1 );

        $this->assertCount( 2, $result );
    }

    /**
     * @covers ArcadiaInventory::remove_item
     */
    public function test_inventory_remove_item_exists() {
        $component = new ArcadiaInventory();

        $result = $component->remove_item( 1, 0 );

        $this->assertTrue( $result );

        $result = $component->get_inventory( 1 );

        $this->assertCount( 0, $result );
    }

    /**
     * @covers ArcadiaInventory::remove_item
     */
    public function test_inventory_remove_item_not_exists() {
        $component = new ArcadiaInventory();

        $result = $component->remove_item( 1, 99 );
        $result = $component->get_inventory( 1 );

        $this->assertCount( 1, $result );
    }


}
