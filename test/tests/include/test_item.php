<?php

class TestArcadiaItem extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->char = FALSE;

        $component = new ArcadiaItem();

        $ag->do_action( 'post_load' );

        $ag->c( 'db' )->execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" ), ( ?, 2, "test2" )',
            array( $component->get_flag_game_meta(),
                   $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        global $ag;

        $ag->c( 'db' )->execute( 'DELETE FROM character_meta', array() );
        $ag->c( 'db' )->execute( 'DELETE FROM game_meta', array() );
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
     * @covers ArcadiaItem::get_item_list
     */
    public function test_get_item_list() {
        $component = new ArcadiaItem();

        $items = $component->get_item_list( array( 1, 2 ) );

        $this->assertCount( 2, $items );
        $this->assertEquals( 'test', $items[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'test2', $items[ 2 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaItem::get_all_items
     */
    public function test_get_all_items() {
        $component = new ArcadiaItem();

        $items = $component->get_all_items();

        $this->assertCount( 2, $items );
        $this->assertEquals( 'test', $items[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'test2', $items[ 2 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaItem::get_items
     */
    public function test_get_items_empty() {
        $component = new ArcadiaItem();

        $items = $component->get_items( 1 );

        $this->assertEmpty( $items );
    }

    /**
     * @covers ArcadiaItem::award_item
     */
    public function test_award_item_no_char() {
        $component = new ArcadiaItem();

        $result = $component->award_item( 1, "test" );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaItem::award_item
     */
    public function test_award_item() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaItem();

        $this->assertTrue( $component->award_item( 1, "test_award" ) );

        $result = $component->get_items( 1 );

        $this->assertCount( 1, $result );
    }


}
