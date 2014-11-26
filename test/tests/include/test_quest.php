<?php

class TestArcadiaQuest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaQuest();

        do_state( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        global $ag;

        $ag->char = FALSE;

        db_execute( 'DELETE FROM character_meta', array() );
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaQuest::__construct
     * @covers ArcadiaQuest::get_flag_game_meta
     */
    public function test_quest_get_flag_game_meta() {
        $component = new ArcadiaQuest();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaQuest::__construct
     * @covers ArcadiaQuest::get_flag_character_meta
     */
    public function test_quest_get_flag_character_meta() {
        $component = new ArcadiaQuest();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaQuest::get_quest
     */
    public function test_get_quest_empty() {
        $component = new ArcadiaQuest();

        $quest = $component->get_quest( -1 );

        $this->assertEquals( FALSE, $quest );
    }

    /**
     * @covers ArcadiaQuest::get_quest
     */
    public function test_get_quest_working() {
        $component = new ArcadiaQuest();

        $quest = $component->get_quest( 1 );

        $this->assertNotEquals( FALSE, $quest );
        $this->assertEquals( 'test', $quest[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaQuest::get_all_quests
     */
    public function test_get_all_quests() {
        $component = new ArcadiaQuest();

        $quests = $component->get_all_quests();

        $this->assertCount( 1, $quests );
        $this->assertEquals( 'test', $quests[ 1 ][ 'meta_value' ] );
    }

}
