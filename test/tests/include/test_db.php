<?php

class TestArcadiaDb extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->c( 'db' )->db_execute(
            'INSERT INTO game_meta ' .
                '( key_type, meta_key, meta_value ) VALUES ' .
                '( 0, 1, "test 1" ), ( 0, 2, "test 2" )',
            array() );
    }

    public function tearDown() {
        global $ag;

        $ag->c( 'db' )->db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaDb::__construct
     */
    public function test_db_constructor() {
        $component = new ArcadiaDb();

        $this->assertNotNull( $component );
    }

    /**
     * @covers ArcadiaDb::db_fetch
     */
    public function test_db_fetch() {
        $component = new ArcadiaDb();

        $obj = $component->db_fetch(
            'SELECT * FROM game_meta WHERE meta_key=1' );

        $this->assertNotFalse( $obj );
        $this->assertEquals( 1, $obj[ 'meta_key' ] );
        $this->assertEquals( 'test 1', $obj[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaDb::db_fetch
     */
    public function test_db_fetch_not_found() {
        $component = new ArcadiaDb();

        $obj = $component->db_fetch(
            'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertFalse( $obj );
    }

    /**
     * @covers ArcadiaDb::db_fetch_all
     */
    public function test_db_fetch_all() {
        $component = new ArcadiaDb();

        $obj = $component->db_fetch_all( 'SELECT * FROM game_meta' );

        $this->assertNotFalse( $obj );
        $this->assertCount( 2, $obj );
    }

    /**
     * @covers ArcadiaDb::db_fetch_all
     */
    public function test_db_fetch_all_key_assoc() {
        $component = new ArcadiaDb();

        $obj = $component->db_fetch_all( 'SELECT * FROM game_meta',
            $args = array(), $key_assoc = 'meta_key' );

        $this->assertNotFalse( $obj );
        $this->assertCount( 2, $obj );
        $this->assertEquals( 'test 1', $obj[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'test 2', $obj[ 2 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaDb::db_fetch_all
     */
    public function test_db_fetch_all_not_found() {
        $component = new ArcadiaDb();

        $obj = $component->db_fetch_all(
            'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertEmpty( $obj );
    }

    /**
     * @covers ArcadiaDb::db_execute
     */
    public function test_db_execute_insert() {
        $component = new ArcadiaDb();

        $result = $component->db_execute( 'INSERT INTO game_meta ' .
                '( key_type, meta_key, meta_value ) ' .
                'VALUES ( 0, 3, "test 3" )' );

        $this->assertTrue( $result );

        $obj = $component->db_fetch_all( 'SELECT * FROM game_meta' );

        $this->assertCount( 3, $obj );
    }

    /**
     * @covers ArcadiaDb::db_execute
     */
    public function test_db_execute_invalid() {
        $component = new ArcadiaDb();

        $result = $component->db_execute( 'INSERT INTO invalid_table ' .
                '( key_type, meta_key, meta_value ) ' .
                'VALUES ( 0, 3, "test 3" )' );

        $this->assertFalse( $result );
    }

}