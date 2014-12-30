<?php

class TestArcadiaUser extends PHPUnit_Framework_TestCase {

    public function setUp() {
        global $ag;

        $ag->do_action( 'post_load' );

        $ag->c( 'db' )->execute(
            'INSERT INTO users ' .
                '( id, user_name, user_pass, email, registered, ' .
                    'activation, status, max_characters ) ' .
                'VALUES ( 1, "name", "pass", "email", ' .
                    '"2014-01-01 01:00:00", "abc", 0, 1 )'
        );
        
        $ag->c( 'db' )->execute(
            'INSERT INTO characters ( id, user_id, character_name ) ' .
                'VALUES ( 1, 1, "character_name" )'
        );

        $this->ag = $ag;
    }

    public function tearDown() {
        global $ag;

        $ag->user = FALSE;

        $ag->c( 'db' )->execute( 'DELETE FROM characters' );
        $ag->c( 'db' )->execute( 'DELETE FROM users' );

        unset( $_SESSION[ 'u' ] );
    }

    /**
     * @covers ArcadiaUser::get_user_by_name
     */
    public function test_get_user_by_name_simple() {
        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $this->assertNotFalse( $user );
        $this->assertEquals( 'name', $user[ 'user_name' ] );
    }

    /**
     * @covers ArcadiaUser::get_user_by_name
     */
    public function test_get_user_by_name_empty() {
        $user = $this->ag->c( 'user' )->get_user_by_name( '' );

        $this->assertFalse( $user );
    }

    /**
     * @covers ArcadiaUser::get_user_by_id
     */
    public function test_get_user_by_id_simple() {
        $user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $this->assertNotFalse( $user );
        $this->assertEquals( 'name', $user[ 'user_name' ] );
    }

    /**
     * @covers ArcadiaUser::get_user_by_id
     */
    public function test_get_user_by_id_empty() {
        $user = $this->ag->c( 'user' )->get_user_by_id( -1 );

        $this->assertFalse( $user );
    }

    /**
     * @covers ArcadiaUser::get_user_by_email
     */
    public function test_get_user_by_email_simple() {
        $user = $this->ag->c( 'user' )->get_user_by_email( 'email' );

        $this->assertNotFalse( $user );
        $this->assertEquals( 'name', $user[ 'user_name' ] );
    }

    /**
     * @covers ArcadiaUser::get_user_by_email
     */
    public function test_get_user_by_email_empty() {
        $user = $this->ag->c( 'user' )->get_user_by_email( '' );

        $this->assertFalse( $user );
    }

    /**
     * @covers ArcadiaUser::game_user_logged_in
     */
    public function test_game_user_logged_in_none() {
        $result = $this->ag->c( 'user' )->game_user_logged_in();

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaUser::game_user_logged_in
     */
    public function test_game_user_logged_in_simple() {
        $_SESSION[ 'u' ] = 1;

        $result = $this->ag->c( 'user' )->game_user_logged_in();

        $this->assertNotFalse( $result );
        $this->assertEquals( 1, $result[ 'id' ] );

        unset( $_SESSION[ 'u' ] );
    }

    /**
     * @covers ArcadiaUser::add_user
     */
    public function test_add_user_simple() {
        $name = 'test_name';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = $this->ag->c( 'user' )->add_user(
            $name, $pass, $email, $send_email = FALSE );

        $this->assertGreaterThan( 1, $user_id );
    }

    /**
     * @covers ArcadiaUser::add_user
     */
    public function test_add_user_duplicate() {
        $name = 'test_name';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = $this->ag->c( 'user' )->add_user(
            $name, $pass, $email, $send_email = FALSE );
        $duplicate_user_id = $this->ag->c( 'user' )->add_user(
            $name, $pass, $email, $send_email = FALSE );

        $this->assertFalse( $duplicate_user_id );
    }

    /**
     * @covers ArcadiaUser::add_user
     */
    public function test_add_user_deny_tags() {
        $name = '<b>name</b>';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = $this->ag->c( 'user' )->add_user(
            $name, $pass, $email, $send_email = FALSE );

        $this->assertFalse( $user_id );
    }

    /**
     * @covers ArcadiaUser::add_user
     */
    public function test_add_user_too_short() {
        $name = 'a';
        $pass = 'test_pass';
        $email = 'test_email';

        $user_id = $this->ag->c( 'user' )->add_user(
            $name, $pass, $email, $send_email = FALSE );

        $this->assertFalse( $user_id );
    }

    /**
     * @covers ArcadiaUser::set_user_status
     */
    public function test_set_user_status_simple() {
        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $status = '123';
        $this->ag->c( 'user' )->set_user_status( $user[ 'id' ], $status );

        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $this->assertEquals( $status, $user[ 'status' ] );
    }

    /**
     * @covers ArcadiaUser::set_user_max_characters
     */
    public function test_set_user_max_characters_simple() {
        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $max_characters = '135';
        $this->ag->c( 'user' )->set_user_max_characters(
            $user[ 'id' ], $max_characters );

        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $this->assertEquals( $max_characters, $user[ 'max_characters' ] );
    }

    /**
     * @covers ArcadiaUser::is_user_dev
     */
    public function test_is_user_dev_empty() {
        $this->assertFalse( $this->ag->c( 'user' )->is_user_dev( array() ) );
    }

    /**
     * @covers ArcadiaUser::is_user_dev
     */
    public function test_is_user_dev_no() {
        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $this->assertFalse( $this->ag->c( 'user' )->is_user_dev( $user ) );
    }

    /**
     * @covers ArcadiaUser::is_user_dev
     */
    public function test_is_user_dev_yes() {
        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $user[ 'status' ] = $this->ag->c( 'common' )->set_bit(
            $user[ 'status' ], ArcadiaUser::USER_STATUS_DEV );

        $this->assertTrue( $this->ag->c( 'user' )->is_user_dev( $user ) );
    }

    /**
     * @covers ArcadiaUser::is_user_active
     */
    public function test_is_user_active_empty() {
        $this->assertFalse( $this->ag->c( 'user' )->is_user_active(
            array() ) );
    }

    /**
     * @covers ArcadiaUser::is_user_active
     */
    public function test_is_user_active_no() {
        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $this->assertFalse( $this->ag->c( 'user' )->is_user_active( $user ) );
    }

    /**
     * @covers ArcadiaUser::is_user_active
     */
    public function test_is_user_active_yes() {
        $user = $this->ag->c( 'user' )->get_user_by_name( 'name' );

        $user[ 'status' ] = $this->ag->c( 'common' )->set_bit(
            $user[ 'status' ], ArcadiaUser::USER_STATUS_ACTIVE );

        $this->assertTrue( $this->ag->c( 'user' )->is_user_active( $user ) );
    }

    /**
     * @covers ArcadiaUser::get_characters_for_user
     */
    public function test_get_characters_for_user_simple() {
        $char_obj = $this->ag->c( 'user' )->get_characters_for_user( 1 );

        $this->assertCount( 1, $char_obj );
        $this->assertEquals( 'character_name',
                             $char_obj[ 1 ][ 'character_name' ] );
    }

    /**
     * @covers ArcadiaUser::get_characters_for_user
     */
    public function test_get_characters_for_user_none() {
        $char_obj = $this->ag->c( 'user' )->get_characters_for_user( 0 );

        $this->assertEmpty( $char_obj );
    }

    /**
     * @covers ArcadiaUser::get_character_by_name
     */
    public function test_get_character_by_name_simple() {
        $char_obj = $this->ag->c( 'user' )->get_character_by_name(
            'character_name' );

        $this->assertEquals( 'character_name', $char_obj[ 'character_name' ] );
    }

    /**
     * @covers ArcadiaUser::get_character_by_name
     */
    public function test_get_character_by_name_none() {
        $char_obj = $this->ag->c( 'user' )->get_character_by_name( '' );

        $this->assertFalse( $char_obj );
    }

    /**
     * @covers ArcadiaUser::get_character_by_id
     */
    public function test_get_character_by_id_simple() {
        $char_obj = $this->ag->c( 'user' )->get_character_by_id( 1 );

        $this->assertEquals( 'character_name', $char_obj[ 'character_name' ] );
    }

    /**
     * @covers ArcadiaUser::get_character_by_id
     */
    public function test_get_character_by_id_none() {
        $char_obj = $this->ag->c( 'user' )->get_character_by_id( 0 );

        $this->assertFalse( $char_obj );
    }

    /**
     * @covers ArcadiaUser::add_character
     */
    public function test_add_character() {
        $name = 'new test name';

        $char_id = $this->ag->c( 'user' )->add_character( 1, $name );
        $char_obj = $this->ag->c( 'user' )->get_character_by_id( $char_id );

        $this->assertEquals( $name, $char_obj[ 'character_name' ] );
    }

    /**
     * @covers ArcadiaUser::user_create_character
     */
    public function test_user_create_character() {
        $this->ag->user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $name = 'test_create';

        $result = $this->ag->c( 'user' )->user_create_character(
            $char_name = $name );

        $char_obj = $this->ag->c( 'user' )->get_character_by_name(
            'test_create' );

        $this->assertTrue( $result );
        $this->assertEquals( $name, $char_obj[ 'character_name' ] );

        unset( $this->ag->user );
    }

    /**
     * @covers ArcadiaUser::user_create_character
     */
    public function test_user_create_character_no_user() {
        $name = 'test_create';

        $result = $this->ag->c( 'user' )->user_create_character(
            $char_name = $name );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaUser::user_create_character
     */
    public function test_user_create_character_get_name() {
        $this->ag->user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $name = 'test_create';
        $_GET[ 'char_name' ] = $name;

        $result = $this->ag->c( 'user' )->user_create_character();

        $char_obj = $this->ag->c( 'user' )->get_character_by_name( $name );

        $this->assertTrue( $result );
        $this->assertEquals( $name, $char_obj[ 'character_name' ] );

        unset( $_GET[ 'char_name' ] );
        unset( $this->ag->user );
    }

    /**
     * @covers ArcadiaUser::user_create_character
     */
    public function test_user_create_character_no_name() {
        $this->ag->user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $result = $this->ag->c( 'user' )->user_create_character();

        $this->assertFalse( $result );

        unset( $this->ag->user );
    }

    /**
     * @covers ArcadiaUser::user_select_character
     */
    public function test_user_select_character() {
        $this->ag->user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $result = $this->ag->c( 'user' )->user_select_character( $id = 1 );

        $char_obj = $this->ag->c( 'user' )->get_character_by_id(
            $_SESSION[ 'c' ] );

        $this->assertTrue( $result );
        $this->assertEquals( 1, $char_obj[ 'id' ] );

        unset( $_SESSION[ 'c' ] );
        unset( $this->ag->user );
    }

    /**
     * @covers ArcadiaUser::user_select_character
     */
    public function test_user_select_character_no_user() {
        $result = $this->ag->c( 'user' )->user_select_character( $id = 1 );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaUser::user_select_character
     */
    public function test_user_select_character_get_id() {
        $this->ag->user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $_GET[ 'id' ] = 1;

        $result = $this->ag->c( 'user' )->user_select_character();

        $char_obj = $this->ag->c( 'user' )->get_character_by_id(
            $_SESSION[ 'c' ] );

        $this->assertTrue( $result );
        $this->assertEquals( 1, $char_obj[ 'id' ] );

        unset( $_GET[ 'id' ] );
        unset( $_SESSION[ 'c' ] );
        unset( $this->ag->user );
    }

    /**
     * @covers ArcadiaUser::user_select_character
     */
    public function test_user_select_character_no_id() {
        $this->ag->user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $result = $this->ag->c( 'user' )->user_select_character();

        $this->assertFalse( $result );

        unset( $this->ag->user );
    }

    /**
     * @covers ArcadiaUser::user_select_character
     */
    public function test_user_select_character_invalid_character() {
        $this->ag->user = $this->ag->c( 'user' )->get_user_by_id( 1 );

        $result = $this->ag->c( 'user' )->user_select_character( $id = -1 );

        $this->assertFalse( $result );

        unset( $this->ag->user );
    }

    /**
     * @covers ArcadiaUser::user_clear_character
     */
    public function test_user_clear_character() {
        $_SESSION[ 'c' ] = 1;

        $this->ag->c( 'user' )->user_clear_character();

        $this->assertArrayNotHasKey( 'c', $_SESSION );

        unset( $_SESSION[ 'c' ] );
    }

    /**
     * @covers ArcadiaUser::game_character_active
     */
    public function test_game_character_active_yes() {
        $_SESSION[ 'c' ] = 1;

        $result = $this->ag->c( 'user' )->game_character_active();

        $this->assertNotFalse( $result );
        $this->assertEquals( 1, $result[ 'id' ] );

        unset( $_SESSION[ 'c' ] );
    }

    /**
     * @covers ArcadiaUser::game_character_active
     */
    public function test_game_character_active_no() {
        $result = $this->ag->c( 'user' )->game_character_active();

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaUser::get_character_meta
     */
    public function test_get_character_meta_no_char() {
        $result = $this->ag->c( 'user' )->get_character_meta( 1 );

        $this->assertEquals( array(), $result );
    }



}
