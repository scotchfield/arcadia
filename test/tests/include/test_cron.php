<?php

class TestArcadiaCron extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) VALUES ( ?, 1, "test" )',
            array( game_meta_type_cron ) );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ::cron_init
     */
    public function test_cron_init() {
        cron_init();

        $this->assertTrue( defined( 'game_meta_type_cron' ) );
    }

    /**
     * @covers ::get_crons
     */
    public function test_get_crons() {
        $cron_obj = get_crons();

        $this->assertCount( 1, $cron_obj );
        $this->assertEquals( 'test', $cron_obj[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ::do_crons
     */
    public function test_do_crons() {
        $result = do_crons();

        $this->assertTrue( $result );
    }

}
