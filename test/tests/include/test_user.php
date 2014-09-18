<?php

class TestArcadiaUser extends PHPUnit_Framework_TestCase {

    public function setUp() {
        do_action( 'post_load' );

        db_execute(
            'INSERT INTO users ' .
                '( id, user_name, user_pass, email, registered, activation, status, max_characters ) ' .
                'VALUES ( 1, "name", "pass", "email", "2014-01-01 01:00:00", "abc", 0, 1 )'
        );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM users', array() );
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
}