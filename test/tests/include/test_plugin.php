<?php

class TestArcadiaPlugin extends PHPUnit_Framework_TestCase {

    public $result;

    public function setUp() {
        $this->result = FALSE;

        add_action( 'test_action', array( $this, 'action_function' ) );
    }

    public function tearDown() {
        remove_action( 'test_action', array( $this, 'action_function' ) );
    }

    public function action_function() {
        $this->result = TRUE;
    }

    /**
     * @covers ::do_action
     */
    public function test_do_action_none() {
        $this->assertNull( do_action( '' ) );
    }

    /**
     * @covers ::do_action
     */
    public function test_do_action_test_action() {
        $this->assertNull( do_action( 'test_action' ) );
        $this->assertTrue( $this->result );
    }

    /**
     * @covers ::do_action
     */
    public function test_do_action_test_action_args() {
        $this->assertNull( do_action( 'test_action', array( TRUE ) ) );
        $this->assertTrue( $this->result );
    }

    /**
     * @covers ::add_action
     */
    public function test_add_action_simple() {
        $test_id = 'test_id';
        $test_function = 'test_function';

        add_action( $test_id, $test_function );

        $this->assertTrue( action_exists( $test_id ) );
    }

    /**
     * @covers ::add_action_priority
     */
    public function test_add_action_priority_simple() {
        $test_id = 'test_id';
        $test_function = 'test_function';

        add_action_priority( $test_id, $test_function );

        $this->assertTrue( action_exists( $test_id ) );
    }

    /**
     * @covers ::remove_action
     */
    public function test_remove_action_simple() {
        remove_action( 'test_action', array( $this, 'action_function' ) );

        $this->assertFalse( action_exists( 'test_action' ) );
    }

    /**
     * @covers ::action_exists
     */
    public function test_action_exists_simple() {
        $this->assertTrue( action_exists( 'test_action' ) );
    }

    /**
     * @covers ::action_exists
     */
    public function test_action_exists_false() {
        $this->assertFalse( action_exists( '123abc' ) );
    }
}
