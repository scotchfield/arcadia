<?php

class TestArcadiaDb extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->c( 'db' )->db_execute(
            'INSERT INTO game_meta ' .
                '( key_type, meta_key, meta_value ) VALUES ' .
                '( 0, 1, "test 1" ), ( 0, 2, "test 2" )',
            array() );

        $ag->c( 'db' )->db_execute( 'DELETE FROM characters' );

        $this->db_addr = DB_ADDRESS;
        $this->db_name = DB_NAME;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASSWORD;
    }

    public function tearDown() {
        global $ag;

        $ag->c( 'db' )->db_execute( 'DELETE FROM characters' );
        $ag->c( 'db' )->db_execute( 'DELETE FROM game_meta' );
    }

    /**
     * @covers ArcadiaDb::__construct
     */
    public function test_db_constructor() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $this->assertNotNull( $component );
    }

    /**
     * @covers ArcadiaDb::__construct
     */
/*    public function test_db_constructor_no_db() {
        $component = new ArcadiaDb( 'a', 'b', 'c', 'd' );

        $this->assertFalse( $component->db );
    }*/ // TODO: make sure we handle this failure

    /**
     * @covers ArcadiaDb::db_fetch
     */
    public function test_db_fetch() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

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
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $obj = $component->db_fetch(
            'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertFalse( $obj );
    }

    /**
     * @covers ArcadiaDb::db_fetch_all
     */
    public function test_db_fetch_all() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $obj = $component->db_fetch_all( 'SELECT * FROM game_meta' );

        $this->assertNotFalse( $obj );
        $this->assertCount( 2, $obj );
    }

    /**
     * @covers ArcadiaDb::db_fetch_all
     */
    public function test_db_fetch_all_key_assoc() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

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
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $obj = $component->db_fetch_all(
            'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertEmpty( $obj );
    }

    /**
     * @covers ArcadiaDb::db_execute
     */
    public function test_db_execute_insert() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

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
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $result = $component->db_execute( 'INSERT INTO invalid_table ' .
                '( key_type, meta_key, meta_value ) ' .
                'VALUES ( 0, 3, "test 3" )' );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaDb::db_last_insert_id
     */
    public function test_db_last_insert_id() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $component->db_execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $id_start = $component->db_last_insert_id();

        $component->db_execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $this->assertEquals( $component->db_last_insert_id(),
                             $id_start + 1 );
    }

    /**
     * @covers ArcadiaDb::db_begin_transaction
     * @covers ArcadiaDb::db_commit
     */
    public function test_db_simple_transaction() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $this->assertTrue( $component->db_begin_transaction() );

        $component->db_execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $this->assertTrue( $component->db_commit() );

        $result = $component->db_fetch_all( 'SELECT * FROM characters' );

        $this->assertCount( 1, $result );
    }

    /**
     * @covers ArcadiaDb::db_begin_transaction
     * @covers ArcadiaDb::db_rollback
     */
    public function test_db_simple_rollback() {
        $component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );

        $this->assertTrue( $component->db_begin_transaction() );

        $component->db_execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $this->assertTrue( $component->db_rollback() );

        $result = $component->db_fetch_all( 'SELECT * FROM characters' );

        $this->assertCount( 0, $result );
    }

}