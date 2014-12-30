<?php

class TestArcadiaDb extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->c( 'db' )->execute(
            'INSERT INTO game_meta ' .
                '( key_type, meta_key, meta_value ) VALUES ' .
                '( 0, 1, "test 1" ), ( 0, 2, "test 2" )',
            array() );

        $ag->c( 'db' )->execute( 'DELETE FROM characters' );

        $this->db_addr = DB_ADDRESS;
        $this->db_name = DB_NAME;
        $this->db_user = DB_USER;
        $this->db_pass = DB_PASSWORD;

        $this->component = new ArcadiaDb(
            $this->db_addr, $this->db_name, $this->db_user, $this->db_pass );
    }

    public function tearDown() {
        global $ag;

        unset( $this->component );

        $ag->c( 'db' )->execute( 'DELETE FROM characters' );
        $ag->c( 'db' )->execute( 'DELETE FROM game_meta' );
    }

    /**
     * @covers ArcadiaDb::__construct
     */
    public function test_db_constructor() {
        $this->assertNotNull( $this->component );
    }

    /**
     * @covers ArcadiaDb::__construct
     */
/*    public function test_db_constructor_no_db() {
        $component = new ArcadiaDb( 'a', 'b', 'c', 'd' );

        $this->assertFalse( $component->db );
    }*/ // TODO: make sure we handle this failure

    /**
     * @covers ArcadiaDb::fetch
     */
    public function test_fetch() {
        $obj = $this->component->fetch(
            'SELECT * FROM game_meta WHERE meta_key=1' );

        $this->assertNotFalse( $obj );
        $this->assertEquals( 1, $obj[ 'meta_key' ] );
        $this->assertEquals( 'test 1', $obj[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaDb::fetch
     */
    public function test_fetch_not_found() {
        $obj = $this->component->fetch(
            'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertFalse( $obj );
    }

    /**
     * @covers ArcadiaDb::fetch_all
     */
    public function test_fetch_all() {
        $obj = $this->component->fetch_all( 'SELECT * FROM game_meta' );

        $this->assertNotFalse( $obj );
        $this->assertCount( 2, $obj );
    }

    /**
     * @covers ArcadiaDb::fetch_all
     */
    public function test_fetch_all_key_assoc() {
        $obj = $this->component->fetch_all( 'SELECT * FROM game_meta',
            $args = array(), $key_assoc = 'meta_key' );

        $this->assertNotFalse( $obj );
        $this->assertCount( 2, $obj );
        $this->assertEquals( 'test 1', $obj[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'test 2', $obj[ 2 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaDb::fetch_all
     */
    public function test_fetch_all_not_found() {
        $obj = $this->component->fetch_all(
            'SELECT * FROM game_meta WHERE meta_key=-1' );

        $this->assertEmpty( $obj );
    }

    /**
     * @covers ArcadiaDb::execute
     */
    public function test_execute_insert() {
        $result = $this->component->execute( 'INSERT INTO game_meta ' .
                '( key_type, meta_key, meta_value ) ' .
                'VALUES ( 0, 3, "test 3" )' );

        $this->assertTrue( $result );

        $obj = $this->component->fetch_all( 'SELECT * FROM game_meta' );

        $this->assertCount( 3, $obj );
    }

    /**
     * @covers ArcadiaDb::execute
     */
    public function test_execute_invalid() {
        $result = $this->component->execute( 'INSERT INTO invalid_table ' .
                '( key_type, meta_key, meta_value ) ' .
                'VALUES ( 0, 3, "test 3" )' );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaDb::last_insert_id
     */
    public function test_last_insert_id() {
        $this->component->execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $id_start = $this->component->last_insert_id();

        $this->component->execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $this->assertEquals( $this->component->last_insert_id(),
                             $id_start + 1 );
    }

    /**
     * @covers ArcadiaDb::begin_transaction
     * @covers ArcadiaDb::commit
     */
    public function test_simple_transaction() {
        $this->assertTrue( $this->component->begin_transaction() );

        $this->component->execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $this->assertTrue( $this->component->commit() );

        $result = $this->component->fetch_all( 'SELECT * FROM characters' );

        $this->assertCount( 1, $result );
    }

    /**
     * @covers ArcadiaDb::begin_transaction
     * @covers ArcadiaDb::rollback
     */
    public function test_simple_rollback() {
        $this->assertTrue( $this->component->begin_transaction() );

        $this->component->execute( 'INSERT INTO characters ' .
            '( user_id, character_name ) VALUES ( \'test\', \'test\' )' );

        $this->assertTrue( $this->component->rollback() );

        $result = $this->component->fetch_all( 'SELECT * FROM characters' );

        $this->assertCount( 0, $result );
    }

}
