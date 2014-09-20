<?php

class TestArcadiaMail extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO game_meta ( key_type, meta_key, meta_value ) ' .
                'VALUES ( ?, 1, "mail 1" ), ( ?, 2, "mail 2" )',
            array( game_meta_type_mail, game_meta_type_mail )
        );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM game_meta', array() );
    }

    /**
     * @covers ::mail_init
     */
    public function test_mail_init() {
        mail_init();

        $this->assertTrue( defined( 'game_meta_type_mail' ) );
        $this->assertTrue( defined( 'game_character_meta_type_mail' ) );
    }

    /**
     * @covers ::get_mail
     */
    public function test_get_mail_simple() {
        $result = get_mail( 1 );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'mail 1', $result[ 'meta_value' ] );
    }

    /**
     * @covers ::get_mail_array
     */
    public function test_get_mail_array() {
        $result = get_mail_array( array( 1, 2 ) );

        $this->assertNotFalse( $result );
        $this->assertEquals( 'mail 1', $result[ 1 ][ 'meta_value' ] );
        $this->assertEquals( 'mail 2', $result[ 2 ][ 'meta_value' ] );
    }

}
