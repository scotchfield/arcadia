<?php

class TestArcadiaBuff extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaBuff();

        do_state( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "test" )',
            array( $component->get_flag_game_meta() ) );

        db_execute(
            'INSERT INTO character_meta ' .
                '( character_id, key_type, meta_key, meta_value ) ' .
                'VALUES ( 1, ?, 1, 12345 )',
            array( $component->get_flag_game_meta() ) );

        $ag->char[ 'meta' ] = get_character_meta( $ag->char[ 'id' ] );
    }

    public function tearDown() {
        global $ag;

        $ag->char = FALSE;

        db_execute( 'DELETE FROM character_meta', array() );
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaBuff::__construct
     * @covers ArcadiaBuff::get_flag_game_meta
     */
    public function test_buff_get_flag_game_meta() {
        $component = new ArcadiaBuff();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaBuff::__construct
     * @covers ArcadiaBuff::get_flag_character_meta
     */
    public function test_buff_get_flag_character_meta() {
        $component = new ArcadiaBuff();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaBuff::get_buff
     */
    public function test_get_buff_empty() {
        $component = new ArcadiaBuff();

        $buff = $component->get_buff( -1 );

        $this->assertEquals( FALSE, $buff );
    }

    /**
     * @covers ArcadiaBuff::get_buff
     */
    public function test_get_buff_working() {
        $component = new ArcadiaBuff();

        $buff = $component->get_buff( 1 );

        $this->assertNotEquals( FALSE, $buff );
        $this->assertEquals( 'test', $buff[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaBuff::get_all_buffs
     */
    public function test_get_all_buffs() {
        $component = new ArcadiaBuff();

        $buffs = $component->get_all_buffs();

        $this->assertCount( 1, $buffs );
        $this->assertEquals( 'test', $buffs[ 1 ][ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaBuff::get_buffs
     */
    public function test_get_buffs_simple() {
        $component = new ArcadiaBuff();

        $buffs = $component->get_buffs( 1 );

        $this->assertCount( 1, $buffs );
    }

    /**
     * @covers ArcadiaBuff::award_buff
     */
    public function test_award_buff_empty() {
        global $ag;

        $component = new ArcadiaBuff();

        $ag->char = FALSE;

        $result = $component->award_buff( 1, 60 );

        $this->assertFalse( $result );
    }

   /**
     * @covers ArcadiaBuff::award_buff
     */
    public function test_award_buff_single() {
        global $ag;

        $component = new ArcadiaBuff();

        $result = $component->award_buff( 1, 60 );

        $this->assertTrue( $result );
        $this->assertArrayHasKey( 1,
            $ag->char[ 'meta' ][ $component->get_flag_game_meta() ] );
    }

    /**
     * @covers ArcadiaBuff::check_buff
     */
    public function test_check_buff_no_char() {
        global $ag;

        $ag->char = FALSE;

        $component = new ArcadiaBuff();

        $result = $component->check_buff( -1 );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaBuff::check_buff
     */
    public function test_check_buff_meta_not_set() {
        global $ag;

        unset( $ag->char[ 'meta' ] );

        $component = new ArcadiaBuff();

        $result = $component->check_buff( -1 );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaBuff::check_buff
     */
    public function test_check_buff_not_set() {
        global $ag;

        $component = new ArcadiaBuff();

        $result = $component->check_buff( -1 );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaBuff::check_buff
     */
    public function test_check_buff_false_by_old_timestamp() {
        global $ag;

        $component = new ArcadiaBuff();

        $result = $component->check_buff( 1 );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaBuff::check_buff
     */
    public function test_check_buff_true_by_new_timestamp() {
        global $ag;

        $component = new ArcadiaBuff();

        $ag->char[ 'meta' ][ $component->get_flag_character_meta() ][ 1 ] = \
            time() + 300;

        $result = $component->check_buff( 1 );

        $this->assertInternalType( 'int', $result );
        $this->assertGreaterThan( 0, $result );
    }




}
