<?php

class TestArcadiaDb extends PHPUnit_Framework_TestCase {

    public function setUp() {
        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) VALUES ' .
                '( 0, 1, "test 1" ), ( 0, 2, "test 2" )',
            array() );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ::db_fetch
     */
    public function test_db_fetch() {
        $obj = db_fetch( 'SELECT * FROM game_meta WHERE meta_key=1' );

        $this->assertNotFalse( $obj );
        $this->assertEquals( 1, $obj[ 'meta_key' ] );
        $this->assertEquals( 'test 1', $obj[ 'meta_value' ] );
    }

    /**
     * @covers ::db_fetch
     */
    public function test_db_fetch_not_found() {
        $obj = db_fetch( 'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertFalse( $obj );
    }

    /**
     * @covers ::db_fetch_all
     */
    public function test_db_fetch_all() {
        $obj = db_fetch_all( 'SELECT * FROM game_meta' );

        $this->assertNotFalse( $obj );
        $this->assertCount( 2, $obj );
    }

    /**
     * @covers ::db_fetch_all
     */
    public function test_db_fetch_all_key_assoc() {
        $obj = db_fetch_all( 'SELECT * FROM game_meta', $args = array(), $key_assoc = 'meta_key' );

        $this->assertNotFalse( $obj );
        $this->assertCount( 2, $obj );
        $this->assertEquals( 'test 1', $obj[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'test 2', $obj[ 2 ][ 'meta_value' ] );
    }

    /**
     * @covers ::db_fetch_all
     */
    public function test_db_fetch_all_not_found() {
        $obj = db_fetch_all( 'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertEmpty( $obj );
    }

    /**
     * @covers ::db_execute
     */
    public function test_db_execute_insert() {
        $result = db_execute( 'INSERT INTO game_meta ' .
                '( key_type, meta_key, meta_value ) ' .
                'VALUES ( 0, 3, "test 3" )' );

        $this->assertTrue( $result );

        $obj = db_fetch_all( 'SELECT * FROM game_meta' );

        $this->assertCount( 3, $obj );
    }

    /**
     * @covers ::db_execute
     */
    public function test_db_execute_invalid() {
        $result = db_execute( 'INSERT INTO invalid_table ' .
                '( key_type, meta_key, meta_value ) ' .
                'VALUES ( 0, 3, "test 3" )' );

        $this->assertFalse( $result );
    }

}