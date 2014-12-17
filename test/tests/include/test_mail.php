<?php

class TestArcadiaMail extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $component = new ArcadiaMail();

        $ag->do_action( 'post_load' );

        $ag->c( 'db' )->db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "mail 1" ), ( ?, 2, "mail 2" )',
            array( $component->get_flag_game_meta(),
                   $component->get_flag_game_meta() )
        );
    }

    public function tearDown() {
        global $ag;

        $ag->c( 'db' )->db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ArcadiaMail::__construct
     * @covers ArcadiaMail::get_flag_game_meta
     */
    public function test_mail_get_flag_game_meta() {
        $component = new ArcadiaMail();

        $this->assertNotNull( $component->get_flag_game_meta() );
    }

    /**
     * @covers ArcadiaMail::__construct
     * @covers ArcadiaMail::get_flag_character_meta
     */
    public function test_mail_get_flag_character_meta() {
        $component = new ArcadiaMail();

        $this->assertNotNull( $component->get_flag_character_meta() );
    }

    /**
     * @covers ArcadiaMail::get_mail
     */
    public function test_get_mail_simple() {
        $component = new ArcadiaMail();

        $result = $component->get_mail( 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'mail 1', $result[ 'meta_value' ] );
    }

    /**
     * @covers ArcadiaMail::get_mail_array
     */
    public function test_get_mail_array() {
        $component = new ArcadiaMail();

        $result = $component->get_mail_array( array( 1, 2 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'mail 1', $result[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'mail 2', $result[ 2 ][ 'meta_value' ] );
    }

}
