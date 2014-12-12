<?php

class TestArcadiaTracking extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaTracking();

        do_action( 'post_load' );

        $ag->c( 'db' )->db_execute(
            'INSERT INTO character_meta ' .
                '( character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( 1, ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        global $ag;

        $ag->char = FALSE;

        $ag->c( 'db' )->db_execute( 'DELETE FROM character_meta', array() );
        $ag->c( 'db' )->db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaTracking::__construct
     * @covers ArcadiaTracking::get_flag_game_meta
     */
    public function test_tracking_get_flag_game_meta() {
        $component = new ArcadiaTracking();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaTracking::__construct
     * @covers ArcadiaTracking::get_flag_character_meta
     */
    public function test_tracking_get_flag_character_meta() {
        $component = new ArcadiaTracking();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaTracking::get_tracking
     */
    public function test_get_tracking_empty() {
        $component = new ArcadiaTracking();

        $result = $component->get_tracking( -1, -1 );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaTracking::get_tracking
     */
    public function test_get_tracking_simple() {
        $component = new ArcadiaTracking();

        $result = $component->get_tracking( 1, 1 );

        $this->assertEquals( $result, array(
            'character_id' => 1,
            'key_type' => $component->get_flag_character_meta(),
            'meta_key' => 1,
            'meta_value' => 'test' ) );
    }

    /**
     * @covers ArcadiaTracking::set_tracking
     */
    public function test_set_tracking_new() {
        $component = new ArcadiaTracking();

        $result = $component->set_tracking( 1, 2, 3 );

        $this->assertTrue( $result );

        $result = $component->get_tracking( 1, 2 );

        $this->assertEquals( $result, array(
            'character_id' => 1,
            'key_type' => $component->get_flag_character_meta(),
            'meta_key' => 2,
            'meta_value' => 3 ) );
    }

    /**
     * @covers ArcadiaTracking::set_tracking
     */
    public function test_set_tracking_overwrite() {
        $component = new ArcadiaTracking();

        $result = $component->set_tracking( 1, 4, 5 );

        $this->assertTrue( $result );

        $result = $component->get_tracking( 1, 4 );

        $this->assertEquals( $result, array(
            'character_id' => 1,
            'key_type' => $component->get_flag_character_meta(),
            'meta_key' => 4,
            'meta_value' => 5 ) );
    }

}
