<?php

class TestArcadiaItem extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $component = new ArcadiaItem();

        do_action( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM character_meta', array() );
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaItem::__construct
     * @covers ArcadiaItem::get_flag_game_meta
     */
    public function test_buff_get_flag_game_meta() {
        $component = new ArcadiaItem();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaItem::__construct
     * @covers ArcadiaItem::get_flag_character_meta
     */
    public function test_buff_get_flag_character_meta() {
        $component = new ArcadiaItem();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaItem::get_item
     */
    public function test_get_item_empty() {
        $component = new ArcadiaItem();

        $item = $component->get_item( -1 );

        $this->assertEquals( FALSE, $item );
    }

    /**
     * @covers ArcadiaItem::get_item
     */
    public function test_get_item_working() {
        $component = new ArcadiaItem();

        $item = $component->get_item( 1 );

        $this->assertNotEquals( FALSE, $item );
        $this->assertEquals( 'test', $item[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaItem::get_all_items
     */
    public function test_get_all_items() {
        $component = new ArcadiaItem();

        $items = $component->get_all_items();

        $this->assertCount( 1, $items );
        $this->assertEquals( 'test', $items[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaItem::get_items
     */
    public function test_get_items_empty() {
        $component = new ArcadiaItem();

        $items = $component->get_items( 1 );

        $this->assertEmpty( $items );
    }


}
