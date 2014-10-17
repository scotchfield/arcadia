<?php

class TestArcadiaHeartbeat extends PHPUnit_Framework_TestCase {

    /**
     * @covers ::heartbeat_init
     */
    public function test_heartbeat_init() {
        heartbeat_init();

        $this->assertTrue( defined( 'game_meta_type_heartbeat' ) );
        $this->assertTrue( defined( 'game_character_meta_type_heartbeat' ) );
    }

}
