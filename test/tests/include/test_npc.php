<?php

class TestArcadiaNpc extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaNpc();

        do_action( 'post_load' );

        $ag->c( 'db' )->db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );
    }

    public function tearDown() {
        global $ag;

        $ag->char = FALSE;

        $ag->c( 'db' )->db_execute( 'DELETE FROM character_meta', array() );
        $ag->c( 'db' )->db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaNpc::__construct
     * @covers ArcadiaNpc::get_flag_game_meta
     */
    public function test_npc_get_flag_game_meta() {
        $component = new ArcadiaNpc();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaNpc::__construct
     * @covers ArcadiaNpc::get_flag_character_meta
     */
    public function test_npc_get_flag_character_meta() {
        $component = new ArcadiaNpc();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaNpc::get_npc
     */
    public function test_get_npc_empty() {
        $component = new ArcadiaNpc();

        $npc = $component->get_npc( -1 );

        $this->assertFalse( $npc );
    }

    /**
     * @covers ArcadiaNpc::get_npc
     */
    public function test_get_npc_working() {
        $component = new ArcadiaNpc();

        $npc = $component->get_npc( 1 );

        $this->assertNotEquals( FALSE, $npc );
        $this->assertEquals( 'test', $npc[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaNpc::get_all_npcs
     */
    public function test_get_all_npcs() {
        $component = new ArcadiaNpc();

        $npcs = $component->get_all_npcs();

        $this->assertCount( 1, $npcs );
        $this->assertEquals( 'test', $npcs[ 1 ][ 'meta_value' ] );
    }

}
