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

}