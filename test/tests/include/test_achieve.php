<?php

class TestArcadiaAchieve extends PHPUnit_Framework_TestCase {

	public function setUp() {
		do_action( 'post_load' );

		db_execute(
			'INSERT INTO game_meta ( key_type, meta_key, meta_value ) VALUES ( ?, 1, "test" )',
			array( game_meta_type_achievement ) );
	}

	public function tearDown() {
		db_execute( 'DELETE FROM game_meta', array() );
	}

    /**
     * @covers ::get_achievement
     */
    public function test_get_achievement_empty() {
        $achievement = get_achievement( -1 );

        $this->assertEquals( FALSE, $achievement );
    }

    /**
     * @covers ::get_achievement
     */
    public function test_get_achievement_working() {
        $achievement = get_achievement( 1 );

        $this->assertNotEquals( FALSE, $achievement );
        $this->assertEquals( 'test', $achievement[ 'meta_value' ] );
    }

}