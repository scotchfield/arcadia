<?php

class TestArcadiaHeartbeat extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $component = new ArcadiaHeartbeat();

        $GLOBALS[ 'character' ] = array( 'id' => 1 );

        db_execute( 'INSERT INTO characters ' .
            '( id, user_id, character_name ) VALUES ' .
            '( 1, 1, \'test\' )' );

        db_execute( 'DELETE FROM character_meta WHERE character_id=1 ' .
            'AND key_type=?',
            array( $component->get_flag_character_meta() ) );
    }

    public function tearDown() {
        unset( $GLOBALS[ 'character' ] );

        db_execute( 'DELETE FROM characters' );
    }

    /**
     * @covers ArcadiaHeartbeat::__construct
     * @covers ArcadiaHeartbeat::get_flag_game_meta
     */
    public function test_buff_get_flag_game_meta() {
        $component = new ArcadiaHeartbeat();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaHeartbeat::__construct
     * @covers ArcadiaHeartbeat::get_flag_character_meta
     */
    public function test_buff_get_flag_character_meta() {
        $component = new ArcadiaHeartbeat();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaHeartbeat::add_heartbeat
     */
    public function test_add_heartbeat_no_character() {
        unset( $GLOBALS[ 'character' ] );

        $component = new ArcadiaHeartbeat();

        $result = $component->add_heartbeat();

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaHeartbeat::add_heartbeat
     */
    public function test_add_heartbeat_simple() {
        $component = new ArcadiaHeartbeat();

        $result = $component->add_heartbeat();

        $this->assertTrue( $result );
    }

    /**
     * @covers ArcadiaHeartbeat::add_heartbeat
     * @covers ArcadiaHeartbeat::get_all_heartbeats
     */
    public function test_get_all_heartbeats_add_one() {
        $component = new ArcadiaHeartbeat();

        $result = $component->get_all_heartbeats();

        $this->assertCount( 0, $result );

        $component->add_heartbeat();
        $result = $component->get_all_heartbeats();

        $this->assertCount( 1, $result );
    }

    /**
     * @covers ArcadiaHeartbeat::get_heartbeat_characters
     */
    public function test_get_heartbeat_characters_empty() {
        $component = new ArcadiaHeartbeat();

        $result = $component->get_heartbeat_characters( 1 );

        $this->assertCount( 0, $result );
    }

    /**
     * @covers ArcadiaHeartbeat::add_heartbeat
     * @covers ArcadiaHeartbeat::get_heartbeat_characters
     */
    public function test_get_heartbeat_characters_single() {
        $component = new ArcadiaHeartbeat();

        $component->add_heartbeat();

        $result = $component->get_heartbeat_characters( 60 );

        $this->assertCount( 1, $result );
    }

}
