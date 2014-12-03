<?php

class TestArcadiaLog extends PHPUnit_Framework_TestCase {

    public function setUp() {
        db_execute( 'DELETE FROM logs' );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM logs' );
    }

    /**
     * @covers ArcadiaLog::log_add
     */
    public function test_log_add_single() {
        $component = new ArcadiaLog();

        $result = $component->log_add( 1, 2, 3 );

        $this->assertTrue( $result );

        $result = db_fetch_all( 'SELECT * FROM logs' );

        $this->assertCount( 1, $result );
    }

}

