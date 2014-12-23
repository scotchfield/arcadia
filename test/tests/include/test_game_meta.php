<?php

class TestArcadiaGameMeta extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->do_action( 'post_load' );

        $ag->c( 'db' )->execute(
            'INSERT INTO character_meta ' .
                '( character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( 1, 1, 1, "test 1 1" ), ( 1, 2, 2, "test 1 2" ), ' .
                '( 2, 1, 1, "test 2 1" ), ( 2, 2, 2, "test 2 2" )'
        );
        $ag->c( 'db' )->execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( 1, 1, "test 1" ), ( 2, 2, "test 2" ), ' .
                '( 2, 3, "test 2 3" )'
        );
    }

    public function tearDown() {
        global $ag;

        $ag->c( 'db' )->execute( 'DELETE FROM character_meta', array() );
        $ag->c( 'db' )->execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaGameMeta::get_game_meta
     */
    public function test_get_game_meta_simple() {
        global $ag;

        $result = $ag->meta->get_game_meta( 1, 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'test 1', $result[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaGameMeta::get_game_meta
     */
    public function test_get_game_meta_false() {
        global $ag;

        $result = $ag->meta->get_game_meta( FALSE, FALSE );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaGameMeta::get_game_meta_by_key
     */
    public function test_get_game_meta_by_key_simple() {
        global $ag;

        $result = $ag->meta->get_game_meta_by_key( 1 );

        $this->assertNotFalse( $result );
        $this->assertCount( 1, $result );
        $this->assertEquals( 'test 1', $result[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaGameMeta::get_game_meta_array
     */
    public function test_get_game_meta_array_simple() {
        global $ag;

        $result = $ag->meta->get_game_meta_array( 1, array( 1 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'test 1', $result[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaGameMeta::get_game_meta_array
     */
    public function test_get_game_meta_array() {
        global $ag;

        $result = $ag->meta->get_game_meta_array( 2, array( 2, 3 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'test 2', $result[ 2 ][ 'meta_value' ] );
        $this->assertEquals( 'test 2 3', $result[ 3 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaGameMeta::get_character_game_meta
     */
    public function test_get_character_game_meta_simple() {
        global $ag;

        $result = $ag->meta->get_character_game_meta( 1, 1, 1 );

        $this->assertNotFalse( $result );
        $this->assertCount( 1, $result );
        $this->assertEquals( 'test 1', $result[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaGameMeta::get_game_meta_all
     */
    public function test_get_game_meta_all_simple() {
        global $ag;

        $result = $ag->meta->get_game_meta_all();

        $this->assertNotFalse( $result );
        $this->assertCount( 2, $result );
    }

    /**
     * @covers ArcadiaGameMeta::update_game_meta
     */
    public function test_update_game_meta_simple() {
        global $ag;

        $new_value = 'update test 1';
        $ag->meta->update_game_meta( 1, 1, $new_value );

        $result = $ag->meta->get_game_meta( 1, 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( $new_value, $result[ 'meta_value' ] );
    }

}
