<?php

class TestArcadiaAchieve extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) VALUES ( ?, 1, "test" )',
            array( game_meta_type_achievement ) );

        $GLOBALS[ 'character' ] = array( 'id' => 1 );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM character_meta', array() );
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

    /**
     * @covers ::get_all_achievements
     */
    public function test_get_all_achievements() {
        $achievements = get_all_achievements();

        $this->assertCount( 1, $achievements );
        $this->assertEquals( 'test', $achievements[ 0 ][ 'meta_value' ] );
    }

    /**
     * @covers ::get_achievements
     */
    public function test_get_achievements_empty() {
        $achievements = get_achievements( 1 );

        $this->assertEmpty( $achievements );
    }

    /**
     * @covers ::award_achievement
     */
    public function test_award_achievement_empty() {
        $GLOBALS[ 'character' ] = FALSE;

        $result = award_achievement( 1 );

        $this->assertFalse( $result );
    }

   /**
     * @covers ::award_achievement
     */
    public function test_award_achievement_single() {
        $result = award_achievement( 1 );

        $this->assertTrue( $result );
        $this->assertArrayHasKey( 1, $GLOBALS[ 'character' ][ 'meta' ][ game_meta_type_achievement ] );
    }

}
