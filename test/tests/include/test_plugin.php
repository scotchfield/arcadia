<?php

class TestArcadiaPlugin extends PHPUnit_Framework_TestCase {

    public $result;

    public function setUp() {
        $this->result = FALSE;

        add_state( 'test_state', array( $this, 'state_function' ) );
    }

    public function tearDown() {
        remove_state( 'test_state', array( $this, 'state_function' ) );
    }

    public function state_function() {
        $this->result = TRUE;
    }

    /**
     * @covers ::do_state
     */
    public function test_do_state_none() {
        $this->assertNull( do_state( '' ) );
    }

    /**
     * @covers ::do_state
     */
    public function test_do_state_test_state() {
        $this->assertNull( do_state( 'test_state' ) );
        $this->assertTrue( $this->result );
    }

    /**
     * @covers ::do_state
     */
    public function test_do_state_test_state_args() {
        $this->assertNull( do_state( 'test_state', array( TRUE ) ) );
        $this->assertTrue( $this->result );
    }

    /**
     * @covers ::add_state
     */
    public function test_add_state_simple() {
        $test_id = 'test_id';
        $test_function = 'test_function';

        add_state( $test_id, $test_function );

        $this->assertTrue( state_exists( $test_id ) );
    }

    /**
     * @covers ::add_state_priority
     */
    public function test_add_state_priority_simple() {
        $test_id = 'test_id';
        $test_function = 'test_function';

        add_state_priority( $test_id, $test_function );

        $this->assertTrue( state_exists( $test_id ) );
    }

    /**
     * @covers ::remove_state
     */
    public function test_remove_state_simple() {
        remove_state( 'test_state', array( $this, 'state_function' ) );

        $this->assertFalse( state_exists( 'test_state' ) );
    }

    /**
     * @covers ::state_exists
     */
    public function test_state_exists_simple() {
        $this->assertTrue( state_exists( 'test_state' ) );
    }

    /**
     * @covers ::state_exists
     */
    public function test_state_exists_false() {
        $this->assertFalse( state_exists( '123abc' ) );
    }
}
