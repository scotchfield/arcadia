<?php

class TestArcadiaUser extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO users ' .
                '( id, user_name, user_pass, email, registered, activation, status, max_characters ) ' .
                'VALUES ( 1, "name", "pass", "email", "2014-01-01 01:00:00", "abc", 0, 1 )'
        );
        
        db_execute(
            'INSERT INTO characters ( id, user_id, character_name ) ' .
                'VALUES ( 1, 1, "character_name" )'
        );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM characters' );
        db_execute( 'DELETE FROM users' );
    }

    /**
     * @covers ::get_user_by_name
     */
    public function test_get_user_by_name_simple() {
        $user = get_user_by_name( 'name' );

        $this->assertNotFalse( $user );
        $this->assertEquals( 'name', $user[ 'user_name' ] );
    }

    /**
     * @covers ::get_user_by_name
     */
    public function test_get_user_by_name_empty() {
        $user = get_user_by_name( '' );

        $this->assertFalse( $user );
    }

    /**
     * @covers ::get_user_by_id
     */
    public function test_get_user_by_id_simple() {
        $user = get_user_by_id( 1 );

        $this->assertNotFalse( $user );
        $this->assertEquals( 'name', $user[ 'user_name' ] );
    }

    /**
     * @covers ::get_user_by_id
     */
    public function test_get_user_by_id_empty() {
        $user = get_user_by_id( -1 );

        $this->assertFalse( $user );
    }

    /**
     * @covers ::get_user_by_email
     */
    public function test_get_user_by_email_simple() {
        $user = get_user_by_email( 'email' );

        $this->assertNotFalse( $user );
        $this->assertEquals( 'name', $user[ 'user_name' ] );
    }

    /**
     * @covers ::get_user_by_email
     */
    public function test_get_user_by_email_empty() {
        $user = get_user_by_email( '' );

        $this->assertFalse( $user );
    }

    /**
     * @covers ::game_user_logged_in
     */
    public function test_game_user_logged_in_none() {
        $result = game_user_logged_in();

        $this->assertFalse( $result );
    }

    /**
     * @covers ::game_user_logged_in
     */
    public function test_game_user_logged_in_simple() {
        $_SESSION[ 'u' ] = 1;

        $result = game_user_logged_in();

        $this->assertNotFalse( $result );
        $this->assertEquals( 1, $result[ 'id' ] );

        unset( $_SESSION[ 'u' ] );
    }

    /**
     * @covers ::add_user
     */
    public function test_add_user_simple() {
        $name = 'test_name';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = add_user( $name, $pass, $email, $send_email = FALSE );

        $this->assertGreaterThan( 1, $user_id );
    }

    /**
     * @covers ::add_user
     */
    public function test_add_user_duplicate() {
        $name = 'test_name';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = add_user( $name, $pass, $email, $send_email = FALSE );
        $duplicate_user_id = add_user( $name, $pass, $email, $send_email = FALSE );

        $this->assertFalse( $duplicate_user_id );
    }

    /**
     * @covers ::add_user
     */
    public function test_add_user_deny_tags() {
        $name = '<b>name</b>';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = add_user( $name, $pass, $email, $send_email = FALSE );

        $this->assertFalse( $user_id );
    }

    /**
     * @covers ::add_user
     */
    public function test_add_user_too_short() {
        $name = 'a';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = add_user( $name, $pass, $email, $send_email = FALSE );

        $this->assertFalse( $user_id );
    }

    /**
     * @covers ::set_user_status
     */
    public function test_set_user_status_simple() {
        $user = get_user_by_name( 'name' );

        $status = '123';
        set_user_status( $user[ 'id' ], $status );

        $user = get_user_by_name( 'name' );

        $this->assertEquals( $status, $user[ 'status' ] );
    }

    /**
     * @covers ::set_user_max_characters
     */
    public function test_set_user_max_characters_simple() {
        $user = get_user_by_name( 'name' );

        $max_characters = '135';
        set_user_max_characters( $user[ 'id' ], $max_characters );

        $user = get_user_by_name( 'name' );

        $this->assertEquals( $max_characters, $user[ 'max_characters' ] );
    }

    /**
     * @covers ::is_user_dev
     */
    public function test_is_user_dev_empty() {
        $this->assertFalse( is_user_dev( array() ) );
    }

    /**
     * @covers ::is_user_dev
     */
    public function test_is_user_dev_no() {
        $user = get_user_by_name( 'name' );

        $this->assertFalse( is_user_dev( $user ) );
    }

    /**
     * @covers ::is_user_dev
     */
    public function test_is_user_dev_yes() {
        $user = get_user_by_name( 'name' );

        $user[ 'status' ] = set_bit( $user[ 'status' ], game_user_status_dev );

        $this->assertTrue( is_user_dev( $user ) );
    }

    /**
     * @covers ::is_user_active
     */
    public function test_is_user_active_empty() {
        $this->assertFalse( is_user_active( array() ) );
    }

    /**
     * @covers ::is_user_active
     */
    public function test_is_user_active_no() {
        $user = get_user_by_name( 'name' );

        $this->assertFalse( is_user_active( $user ) );
    }

    /**
     * @covers ::is_user_active
     */
    public function test_is_user_active_yes() {
        $user = get_user_by_name( 'name' );

        $user[ 'status' ] = set_bit( $user[ 'status' ], game_user_status_active );

        $this->assertTrue( is_user_active( $user ) );
    }

    /**
     * @covers ::get_characters_for_user
     */
    public function test_get_characters_for_user_simple() {
        $char_obj = get_characters_for_user( 1 );

        $this->assertCount( 1, $char_obj );
        $this->assertEquals( 'character_name', $char_obj[ 1 ][ 'character_name' ] );
    }

    /**
     * @covers ::get_characters_for_user
     */
    public function test_get_characters_for_user_none() {
        $char_obj = get_characters_for_user( 0 );

        $this->assertEmpty( $char_obj );
    }

    /**
     * @covers ::get_character_by_name
     */
    public function test_get_character_by_name_simple() {
        $char_obj = get_character_by_name( 'character_name' );

        $this->assertEquals( 'character_name', $char_obj[ 'character_name' ] );
    }

    /**
     * @covers ::get_character_by_name
     */
    public function test_get_character_by_name_none() {
        $char_obj = get_character_by_name( '' );

        $this->assertFalse( $char_obj );
    }

    /**
     * @covers ::get_character_by_id
     */
    public function test_get_character_by_id_simple() {
        $char_obj = get_character_by_id( 1 );

        $this->assertEquals( 'character_name', $char_obj[ 'character_name' ] );
    }

    /**
     * @covers ::get_character_by_id
     */
    public function test_get_character_by_id_none() {
        $char_obj = get_character_by_id( 0 );

        $this->assertFalse( $char_obj );
    }

    /**
     * @covers ::add_character
     */
    public function test_add_character() {
        $name = 'new test name';

        $char_id = add_character( 1, $name );

        $char_obj = get_character_by_id( $char_id );

        $this->assertEquals( $name, $char_obj[ 'character_name' ] );
    }

    /**
     * @covers ::user_create_character
     */
    public function test_user_create_character() {
        $GLOBALS[ 'user' ] = get_user_by_id( 1 );

        $name = 'test_create';

        $result = user_create_character( $char_name = $name );

        $char_obj = get_character_by_name( 'test_create' );

        $this->assertTrue( $result );
        $this->assertEquals( $name, $char_obj[ 'character_name' ] );

        unset( $GLOBALS[ 'user' ] );
    }

    /**
     * @covers ::user_create_character
     */
    public function test_user_create_character_no_user() {
        $name = 'test_create';

        $result = user_create_character( $char_name = $name );

        $this->assertFalse( $result );
    }

    /**
     * @covers ::user_create_character
     */
    public function test_user_create_character_get_name() {
        $GLOBALS[ 'user' ] = get_user_by_id( 1 );

        $name = 'test_create';
        $_GET[ 'char_name' ] = $name;

        $result = user_create_character();

        $char_obj = get_character_by_name( $name );

        $this->assertTrue( $result );
        $this->assertEquals( $name, $char_obj[ 'character_name' ] );

        unset( $_GET[ 'char_name' ] );
        unset( $GLOBALS[ 'user' ] );
    }

    /**
     * @covers ::user_create_character
     */
    public function test_user_create_character_no_name() {
        $GLOBALS[ 'user' ] = get_user_by_id( 1 );

        $result = user_create_character();

        $this->assertFalse( $result );
        
        unset( $GLOBALS[ 'user' ] );
    }

    /**
     * @covers ::user_select_character
     */
    public function test_user_select_character() {
        $GLOBALS[ 'user' ] = get_user_by_id( 1 );

        $result = user_select_character( $id = 1 );

        $char_obj = get_character_by_id( $_SESSION[ 'c' ] );

        $this->assertTrue( $result );
        $this->assertEquals( 1, $char_obj[ 'id' ] );

        unset( $_SESSION[ 'c' ] );
        unset( $GLOBALS[ 'user' ] );
    }

    /**
     * @covers ::user_select_character
     */
    public function test_user_select_character_no_user() {
        $result = user_select_character( $id = 1 );

        $this->assertFalse( $result );
    }

    /**
     * @covers ::user_select_character
     */
    public function test_user_select_character_get_id() {
        $GLOBALS[ 'user' ] = get_user_by_id( 1 );

        $_GET[ 'id' ] = 1;

        $result = user_select_character();

        $char_obj = get_character_by_id( $_SESSION[ 'c' ] );

        $this->assertTrue( $result );
        $this->assertEquals( 1, $char_obj[ 'id' ] );

        unset( $_GET[ 'id' ] );
        unset( $_SESSION[ 'c' ] );
        unset( $GLOBALS[ 'user' ] );
    }

    /**
     * @covers ::user_select_character
     */
    public function test_user_select_character_no_id() {
        $GLOBALS[ 'user' ] = get_user_by_id( 1 );

        $result = user_select_character();

        $this->assertFalse( $result );

        unset( $GLOBALS[ 'user' ] );
    }

    /**
     * @covers ::user_select_character
     */
    public function test_user_select_character_invalid_character() {
        $GLOBALS[ 'user' ] = get_user_by_id( 1 );

        $result = user_select_character( $id = -1 );

        $this->assertFalse( $result );

        unset( $GLOBALS[ 'user' ] );
    }

    /**
     * @covers ::user_clear_character
     */
    public function test_user_clear_character() {
        $_SESSION[ 'c' ] = 1;

        user_clear_character();

        $this->assertArrayNotHasKey( 'c', $_SESSION );
    }

    /**
     * @covers ::game_character_active
     */
    public function test_game_character_active_yes() {
        $_SESSION[ 'c' ] = 1;

        $result = game_character_active();

        $this->assertNotFalse( $result );
        $this->assertEquals( 1, $result[ 'id' ] );

        unset( $_SESSION[ 'c' ] );
    }

    /**
     * @covers ::game_character_active
     */
    public function test_game_character_active_no() {
        $result = game_character_active();

        $this->assertFalse( $result );
    }


}