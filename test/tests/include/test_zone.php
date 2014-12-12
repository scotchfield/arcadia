<?php

class TestArcadiaZone extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaZone();

        do_action( 'post_load' );

        $ag->c( 'db' )->db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        global $ag;

        $ag->char = FALSE;

        $ag->c( 'db' )->db_execute( 'DELETE FROM character_meta', array() );
        $ag->c( 'db' )->db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaZone::__construct
     * @covers ArcadiaZone::get_flag_game_meta
     */
    public function test_zone_get_flag_game_meta() {
        $component = new ArcadiaZone();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaZone::__construct
     * @covers ArcadiaZone::get_flag_character_meta
     */
    public function test_zone_get_flag_character_meta() {
        $component = new ArcadiaZone();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaZone::get_zone
     */
    public function test_get_zone_empty() {
        $component = new ArcadiaZone();

        $zone = $component->get_zone( -1 );

        $this->assertEquals( FALSE, $zone );
    }

    /**
     * @covers ArcadiaZone::get_zone
     */
    public function test_get_zone_working() {
        $component = new ArcadiaZone();

        $zone = $component->get_zone( 1 );

        $this->assertNotEquals( FALSE, $zone );
        $this->assertEquals( 'test', $zone[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaZone::get_all_zones
     */
    public function test_get_all_zones() {
        $component = new ArcadiaZone();

        $zones = $component->get_all_zones();

        $this->assertCount( 1, $zones );
        $this->assertEquals( 'test', $zones[ 1 ][ 'meta_value' ] );
    }

}
