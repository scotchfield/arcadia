<?php

class TestArcadiaAchieve extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $component = new ArcadiaAchievement();

        do_state( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );

        $GLOBALS[ 'character' ] = array( 'id' => 1 );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM character_meta', array() );
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaAchievement::__construct
     * @covers ArcadiaAchievement::get_flag_game_meta
     */
    public function test_buff_get_flag_game_meta() {
        $component = new ArcadiaAchievement();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaAchievement::__construct
     * @covers ArcadiaAchievement::get_flag_character_meta
     */
    public function test_buff_get_flag_character_meta() {
        $component = new ArcadiaAchievement();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaAchievement::get_achievement
     */
    public function test_get_achievement_empty() {
        $component = new ArcadiaAchievement();

        $achievement = $component->get_achievement( -1 );

        $this->assertEquals( FALSE, $achievement );
    }

    /**
     * @covers ArcadiaAchievement::get_achievement
     */
    public function test_get_achievement_working() {
        $component = new ArcadiaAchievement();

        $achievement = $component->get_achievement( 1 );

        $this->assertNotEquals( FALSE, $achievement );
        $this->assertEquals( 'test', $achievement[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaAchievement::get_all_achievements
     */
    public function test_get_all_achievements() {
        $component = new ArcadiaAchievement();

        $achievements = $component->get_all_achievements();

        $this->assertCount( 1, $achievements );
        $this->assertEquals( 'test', $achievements[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaAchievement::get_achievements
     */
    public function test_get_achievements_empty() {
        $component = new ArcadiaAchievement();

        $achievements = $component->get_achievements( 1 );

        $this->assertEmpty( $achievements );
    }

    /**
     * @covers ArcadiaAchievement::award_achievement
     */
    public function test_award_achievement_empty() {
        $component = new ArcadiaAchievement();

        $GLOBALS[ 'character' ] = FALSE;

        $result = $component->award_achievement( 1 );

        $this->assertFalse( $result );
    }

   /**
     * @covers ArcadiaAchievement::award_achievement
     */
    public function test_award_achievement_single() {
        $component = new ArcadiaAchievement();

        $result = $component->award_achievement( 1 );

        $this->assertTrue( $result );
        $this->assertArrayHasKey( 1,
            $GLOBALS[ 'character' ][ 'meta' ][
                $component->get_flag_game_meta() ] );
    }

   /**
     * @covers ArcadiaAchievement::award_achievement
     */
    public function test_award_achievement_double_false() {
        $component = new ArcadiaAchievement();

        $component->award_achievement( 1 );
        $result = $component->award_achievement( 1 );

        $this->assertFalse( $result );
    }

}
