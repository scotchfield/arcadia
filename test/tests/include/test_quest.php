<?php

class TestArcadiaQuest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "quest 1" ), ( ?, 2, "quest 2" )',
            array( game_meta_type_quest, game_meta_type_quest )
        );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ::quest_init
     */
    public function test_quest_init() {
        quest_init();

        $this->assertTrue( defined( 'game_meta_type_quest' ) );
        $this->assertTrue( defined( 'game_character_meta_type_quest' ) );
    }

    /**
     * @covers ::get_quest
     */
    public function test_get_quest_simple() {
        $result = get_quest( 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'quest 1', $result[ 'meta_value' ] );
    }

    /**
     * @covers ::get_quest_array
     */
    public function test_get_quest_array() {
        $result = get_quest_array( array( 1, 2 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'quest 1', $result[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'quest 2', $result[ 2 ][ 'meta_value' ] );
    }

}
