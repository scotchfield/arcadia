<?php

class TestArcadiaLog extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->c( 'db' )->db_execute( 'DELETE FROM logs' );
    }

    /**
     * @covers ArcadiaLog::__construct
     */
    public function test_log_construct() {
        $component = new ArcadiaLog( new ArcadiaGame() );

        $this->assertNotNull( $component );
    }

    /**
     * @covers ArcadiaLog::log_add
     */
    public function test_log_add_no_db() {
        $component = new ArcadiaLog( new ArcadiaGame() );

        $result = $component->log_add( 1, 2, 3 );
        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLog::log_add
     */
    public function test_log_add_single() {
        $ag = new ArcadiaGame();
        $ag->set_component( 'db', new ArcadiaDb(
            DB_ADDRESS, DB_NAME, DB_USER, DB_PASSWORD ) );

        $component = new ArcadiaLog( $ag );

        $result = $component->log_add( 1, 2, 3 );
        $this->assertTrue( $result );

        $result = $ag->c( 'db' )->db_fetch_all( 'SELECT * FROM logs' );
        $this->assertCount( 1, $result );
    }

}

