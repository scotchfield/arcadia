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
        global $ag;

        $ag->clear_args();

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
       $ag->set_arg( 'user', $this->username );

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
       $ag->set_arg( 'user', $this->username );
       $ag->set_arg( 'pass', $this->password );

       $result = $component->content_login();

       $this->assertTrue( $result );
    }

    /**
     * @covers ArcadiaLogin::content_register
     */
    public function test_login_content_register_no_state() {
        global $ag;

        $component = new ArcadiaLogin();

        $result = $component->content_register();

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_register
     */
    public function test_login_content_register_no_user() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'register' );

       $result = $component->content_register();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_register
     */
    public function test_login_content_register_no_password() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'register' );
       $ag->set_arg( 'user', $this->username );

       $result = $component->content_register();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_register
     */
    public function test_login_content_register_no_email() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'register' );
       $ag->set_arg( 'user', $this->username );
       $ag->set_arg( 'pass', $this->password );

       $result = $component->content_register();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_register
     */
    public function test_login_content_register_user_exists() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'register' );
       $ag->set_arg( 'user', $this->username );
       $ag->set_arg( 'pass', $this->password );
       $ag->set_arg( 'email', $this->email );

       $result = $component->content_register();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_register
     */
    public function test_login_content_register_email_exists() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'register' );
       $ag->set_arg( 'user', 'test_user_new' );
       $ag->set_arg( 'pass', 'test_password_new' );
       $ag->set_arg( 'email', $this->email );

       $result = $component->content_register();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_register
     */
    public function test_login_content_register_success() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'register' );
       $ag->set_arg( 'user', 'test_user_new' );
       $ag->set_arg( 'pass', 'test_password_new' );
       $ag->set_arg( 'email', 'test_email_new' );

       $result = $component->content_register(
           array( 'send_email' => FALSE ) );

       $this->assertTrue( $result );
    }

    /**
     * @covers ArcadiaLogin::content_activate
     */
    public function test_login_content_activate_no_state() {
        global $ag;

        $component = new ArcadiaLogin();

        $result = $component->content_activate();

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_activate
     */
    public function test_login_content_activate_no_user() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'activate' );

       $result = $component->content_activate();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_activate
     */
    public function test_login_content_activate_user_with_no_activate() {
       global $ag;

       $component = new ArcadiaLogin();

       $ag->set_state( 'activate' );
       $ag->set_arg( 'user', $this->username );

       $result = $component->content_activate();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_activate
     */
    public function test_login_content_activate_user_with_valid_activate() {
       global $ag;

       $user = get_user_by_name( $this->username );

       $component = new ArcadiaLogin();

       $ag->set_state( 'activate' );
       $ag->set_arg( 'user', $user[ 'id' ] );
       $ag->set_arg( 'activate', $user[ 'activation' ] );

       $result = $component->content_activate();

       $this->assertTrue( $result );
    }

    /**
     * @covers ArcadiaLogin::content_activate
     */
    public function test_login_content_activate_user_already_active() {
       global $ag;

       $user = get_user_by_name( $this->username );
       set_user_status( $user[ 'id' ],
           $ag->c( 'common' )->set_bit( $user[ 'status' ],
                                        game_user_status_active ) );

       $component = new ArcadiaLogin();

       $ag->set_state( 'activate' );
       $ag->set_arg( 'user', $user[ 'id' ] );
       $ag->set_arg( 'activate', $user[ 'activation' ] );

       $result = $component->content_activate();

       $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaLogin::content_activate
     */
    public function test_login_content_activate_invalid_activate() {
       global $ag;

       $user = get_user_by_name( $this->username );

       $component = new ArcadiaLogin();

       $ag->set_state( 'activate' );
       $ag->set_arg( 'user', $user[ 'id' ] );
       $ag->set_arg( 'activate', 'invalid_activate' );

       $result = $component->content_activate();

       $this->assertFalse( $result );
    }


}