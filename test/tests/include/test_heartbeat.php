<?php

class TestArcadiaHeartbeat extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $GLOBALS[ 'character' ] = array( 'id' => 1 );

        db_execute( 'DELETE FROM character_meta WHERE character_id=1 ' .
            'AND key_type=?',
            array( game_character_meta_type_heartbeat ) );
    }

    public function tearDown() {
        unset( $GLOBALS[ 'character' ] );
    }

    /**
     * @covers ::heartbeat_init
     */
    public function test_heartbeat_init() {
        heartbeat_init();

        $this->assertTrue( defined( 'game_meta_type_heartbeat' ) );
        $this->assertTrue( defined( 'game_character_meta_type_heartbeat' ) );
    }

    /**
     * @covers ::add_heartbeat
     */
    public function test_add_heartbeat_no_character() {
        unset( $GLOBALS[ 'character' ] );

        $result = add_heartbeat();

        $this->assertFalse( $result );
    }

    /**
     * @covers ::add_heartbeat
     */
    public function test_add_heartbeat_simple() {
        $result = add_heartbeat();

        $this->assertTrue( $result );
    }

    /**
     * @covers ::add_heartbeat
     * @covers ::get_all_heartbeats
     */
    public function test_get_all_heartbeats_add_one() {
        $result = get_all_heartbeats();

        $this->assertCount( 0, $result );

        add_heartbeat();
        $result = get_all_heartbeats();

        $this->assertCount( 1, $result );
    }

}
