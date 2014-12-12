<?php

class TestArcadiaCron extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $component = new ArcadiaCron();

        do_action( 'post_load' );

        $ag->c( 'db' )->db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        global $ag;

        $ag->c( 'db' )->db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaCron::__construct
     * @covers ArcadiaCron::get_flag_game_meta
     */
    public function test_buff_get_flag_game_meta() {
        $component = new ArcadiaCron();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaCron::__construct
     * @covers ArcadiaCron::get_flag_character_meta
     */
    public function test_buff_get_flag_character_meta() {
        $component = new ArcadiaCron();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaCron::get_crons
     */
    public function test_get_crons() {
        $component = new ArcadiaCron();

        $cron_obj = $component->get_crons();

        $this->assertCount( 1, $cron_obj );
        $this->assertEquals( 'test', $cron_obj[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaCron::do_crons
     */
    public function test_do_crons() {
        $component = new ArcadiaCron();

        $result = $component->do_crons();

        $this->assertTrue( $result );
    }

}
