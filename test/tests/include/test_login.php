<?php

class TestArcadiaLogin extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->username = 'test_user';
        $this->password = 'test_pass';
        $this->email = 'test@test.com';

        add_user(
            $this->username,
            password_hash( $this->password, PASSWORD_DEFAULT ),
            $this->email,
            $send_email = FALSE
        );
    }

    public function tearDown() {
        db_execute( 'DELETE FROM USERS' );
    }

    /**
     * @covers ArcadiaLogin::__construct
     */
    public function test_login_get_instance() {
        $component = new ArcadiaLogin();

        $this->assertNotNull( $component );
    }

    /**
     * @covers ArcadiaLogin::content_login
     */
    public function test_login_content_login_no_state() {
        global $ag;

        $component = new ArcadiaLogin();

        $result = $component->content_login();

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_login
     */
    public function test_login_content_login_user_does_not_exist() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'login' );

       $result = $component->content_login();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_login
     */
    public function test_login_content_login_user_bad_password() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'login' );
       $ag->set_state_arg( 'user', $this->username );

       $result = $component->content_login();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_login
     */
    public function test_login_content_login_user_success() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'login' );
       $ag->set_state_arg( 'user', $this->username );
       $ag->set_state_arg( 'pass', $this->password );

       $result = $component->content_login();

       $this->assertTrue( $result );
    }

}