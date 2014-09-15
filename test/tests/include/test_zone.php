<?php

class TestArcadiaZone extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "zone 1" ), ( ?, 2, "zone 2" )',
            array( game_meta_type_zone, game_meta_type_zone )
        );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ::zone_init
     */
    public function test_zone_init() {
        zone_init();

        $this->assertTrue( defined( 'game_meta_type_zone' ) );
        $this->assertTrue( defined( 'game_character_meta_type_zone' ) );
    }

    /**
     * @covers ::get_zone
     */
    public function test_get_zone_simple() {
        $result = get_zone( 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'zone 1', $result[ 'meta_value' ] );
    }

    /**
     * @covers ::get_zone_array
     */
    public function test_get_zone_array() {
        $result = get_zone_array( array( 1, 2 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'zone 1', $result[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'zone 2', $result[ 2 ][ 'meta_value' ] );
    }

}
