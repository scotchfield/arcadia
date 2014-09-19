<?php

class TestArcadiaGameMeta extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO character_meta ' .
                '( character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( 1, 1, 1, "test 1 1" ), ( 1, 2, 2, "test 1 2" ), ' .
                '( 2, 1, 1, "test 2 1" ), ( 2, 2, 2, "test 2 2" )'
        );
        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( 1, 1, "test 1" ), ( 2, 2, "test 2" ), ' .
                '( 2, 3, "test 2 3" )'
        );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM character_meta', array() );
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ::get_game_meta
     */
    public function test_get_game_meta_simple() {
        $result = get_game_meta( 1, 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'test 1', $result[ 'meta_value' ] );
    }

    /**
     * @covers ::get_game_meta
     */
    public function test_get_game_meta_false() {
        $result = get_game_meta( FALSE, FALSE );

        $this->assertFalse( $result );
    }

    /**
     * @covers ::get_game_meta_by_key
     */
    public function test_get_game_meta_by_key_simple() {
        $result = get_game_meta_by_key( 1 );

        $this->assertNotFalse( $result );
        $this->assertCount( 1, $result );
        $this->assertEquals( 'test 1', $result[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ::get_game_meta_array
     */
    public function test_get_game_meta_array_simple() {
        $result = get_game_meta_array( 1, array( 1 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'test 1', $result[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ::get_game_meta_array
     */
    public function test_get_game_meta_array() {
        $result = get_game_meta_array( 2, array( 2, 3 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'test 2', $result[ 2 ][ 'meta_value' ] );
        $this->assertEquals( 'test 2 3', $result[ 3 ][ 'meta_value' ] );
    }

    /**
     * @covers ::get_character_game_meta
     */
    public function test_get_character_game_meta_simple() {
        $result = get_character_game_meta( 1, 1, 1 );

        $this->assertNotFalse( $result );
        $this->assertCount( 1, $result );
        $this->assertEquals( 'test 1', $result[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ::get_game_meta_all
     */
    public function test_get_game_meta_all_simple() {
        $result = get_game_meta_all();

        $this->assertNotFalse( $result );
        $this->assertCount( 2, $result );
    }

    /**
     * @covers ::update_game_meta
     */
    public function test_update_game_meta_simple() {
        $new_value = 'update test 1';
        update_game_meta( 1, 1, $new_value );

        $result = get_game_meta( 1, 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( $new_value, $result[ 'meta_value' ] );
    }

}
