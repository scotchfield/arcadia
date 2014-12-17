<?php

class TestArcadiaGame extends PHPUnit_Framework_TestCase {

    public $result;

    public function setUp() {
        $this->result = FALSE;

//        add_state( 'test_state', FALSE, array( $this, 'state_function' ) );
    }

    public function tearDown() {
//        remove_state( 'test_state', FALSE, array( $this, 'state_function' ) );
    }

    public function state_function() {
        $this->result = TRUE;
    }

    /**
     * @covers ArcadiaGame::__construct
     */
    public function test_game_new() {
        $component = new ArcadiaGame();

        $this->assertNotNull( $component );
    }

    /**
     * @covers ArcadiaGame::get_state
     * @covers ArcadiaGame::set_state
     */
    public function test_game_set_state() {
        $game = new ArcadiaGame();

    	$original = $game->get_state();

    	$test = 'test_state';
        $game->set_state( $test );

    	$result = $game->get_state();

    	$this->assertNotEquals( $original, $result );
    	$this->assertEquals( $test, $result );
    }

    /**
     * @covers ArcadiaGame::get_arg
     * @covers ArcadiaGame::set_arg
     */
    public function test_game_set_and_get_arg() {
        $game = new ArcadiaGame();

        $arg_k = 'test_k';
        $arg_v = 'test_v';

        $game->set_arg( $arg_k, $arg_v );

        $this->assertEquals( $arg_v, $game->get_arg( $arg_k ) );
    }

    /**
     * @covers ArcadiaGame::get_arg
     */
    public function test_game_get_arg_not_set() {
        $game = new ArcadiaGame();

        $this->assertFalse( $game->get_arg( 'test_not_set' ) );
    }

    /**
     * @covers ArcadiaGame::set_arg
     * @covers ArcadiaGame::clear_args
     */
    public function test_game_clear_args() {
        $game = new ArcadiaGame();

        $test_value = 'test_k';

        $game->set_arg( $test_value, $test_value );
        $game->clear_args();

        $this->assertFalse( $game->get_arg( $test_value ) );
    }

    /**
     * @covers ArcadiaGame::set_component
     * @covers ArcadiaGame::get_component
     * @covers ArcadiaGame::c
     */
    public function test_game_set_get_component() {
        $game = new ArcadiaGame();

        $component = array( 1 => 2 );

        $game->set_component( 'test', $component );

        $this->assertEquals( $component, $game->get_component( 'test' ) );
        $this->assertEquals( $component, $game->c( 'test' ) );
    }

    /**
     * @covers ArcadiaGame::get_component
     */
    public function test_game_get_component_does_not_exist() {
        $game = new ArcadiaGame();

        $this->assertFalse( $game->get_component( 'test' ) );
    }

    /**
     * @covers ArcadiaGame::do_action
     */
    public function test_do_action_none() {
        $game = new ArcadiaGame();

        $this->assertNull( $game->do_action( '' ) );
    }

    /**
     * @covers ArcadiaGame::do_action
     */
    public function test_do_action_none_with_existing_states() {
        $game = new ArcadiaGame();

        $game->add_state( 'test_state', FALSE,
            array( $this, 'state_function' ) );

        $this->assertNull( $game->do_action( '' ) );
    }

    /**
     * @covers ArcadiaGame::do_action
     */
    public function test_do_action_valid_but_ignored() {
        $game = new ArcadiaGame();

        $game->set_state( 'test' );

        $game->add_state( 'test_state', 'never_there',
            array( $this, 'state_function' ) );

        $this->assertNull( $game->do_action( 'test_state' ) );
    }

    /**
     * @covers ArcadiaGame::do_action
     */
    public function test_do_action_test_state() {
        $game = new ArcadiaGame();

        $game->add_state( 'test_state', FALSE,
            array( $this, 'state_function' ) );

        $this->assertNull( $game->do_action( 'test_state' ) );
        $this->assertTrue( $this->result );
    }

    /**
     * @covers ArcadiaGame::do_action
     */
    public function test_do_action_test_args() {
        $game = new ArcadiaGame();

        $game->add_state( 'test_state', FALSE,
            array( $this, 'state_function' ) );

        $this->assertNull( $game->do_action( 'test_state', array( TRUE ) ) );
        $this->assertTrue( $this->result );
    }

    /**
     * @covers ArcadiaGame::add_state
     */
    public function test_add_state_simple() {
        $game = new ArcadiaGame();

        $test_id = 'test_id';
        $test_function = 'test_function';

        $game->add_state( $test_id, FALSE, $test_function );

        $this->assertTrue( $game->state_exists( $test_id ) );
    }

    /**
     * @covers ArcadiaGame::add_state_priority
     */
    public function test_add_state_priority_simple() {
        $game = new ArcadiaGame();

        $test_id = 'test_id';
        $test_function = 'test_function';

        $game->add_state_priority( $test_id, FALSE, $test_function );

        $this->assertTrue( $game->state_exists( $test_id ) );
    }

    /**
     * @covers ArcadiaGame::remove_state
     */
    public function test_remove_state_simple() {
        $game = new ArcadiaGame();

        $game->add_state( 'test_state', FALSE,
            array( $this, 'state_function' ) );
        $game->remove_state( 'test_state', FALSE,
            array( $this, 'state_function' ) );

        $this->assertFalse( $game->state_exists( 'test_state' ) );
    }

    /**
     * @covers ArcadiaGame::state_exists
     */
    public function test_state_exists_simple() {
        $game = new ArcadiaGame();

        $game->add_state( 'test_state', FALSE,
            array( $this, 'state_function' ) );
        
        $this->assertTrue( $game->state_exists( 'test_state' ) );
    }

    /**
     * @covers ArcadiaGame::state_exists
     */
    public function test_state_exists_false() {
        $game = new ArcadiaGame();

        $this->assertFalse( $game->state_exists( '123abc' ) );
    }

    /**
     * @covers ArcadiaGame::char_meta
     */
    public function test_char_meta_no_char() {
        $game = new ArcadiaGame();

        $this->assertFalse( $game->char_meta( 1, 2, 3 ) );
    }

    /**
     * @covers ArcadiaGame::char_meta
     */
    public function test_char_meta_no_meta_value_set() {
        $game = new ArcadiaGame();

        $game->char = array( 'id' => 1 );

        $this->assertEquals( 3, $game->char_meta( 1, 2, 3 ) );
    }

    /**
     * @covers ArcadiaGame::char_meta
     */
    public function test_char_meta_success() {
        $game = new ArcadiaGame();

        $game->char = array( 'meta' => array( 1 => array( 2 => 'test' ) ) );

        $this->assertEquals( 'test', $game->char_meta( 1, 2, 3 ) );
    }

    /**
     * @covers ArcadiaGame::set_redirect_header
     * @covers ArcadiaGame::redirect_header
     */
    public function test_set_header() {
        $game = new ArcadiaGame();

        $header = 'test_header';

        $game->set_redirect_header( $header );

        $this->assertEquals( $game->redirect_header(), $header );
    }

    /**
     * @covers ArcadiaGame::debug_print
     */
    public function test_debug_print_array() {
        $game = new ArcadiaGame();

        $this->assertNull( $game->debug_print( array( 'test' ) ) );
    }

    /**
     * @covers ArcadiaGame::debug_print
     */
    public function test_debug_print_string() {
        $game = new ArcadiaGame();

        $this->assertNull( $game->debug_print( 'test' ) );
    }

    /**
     * @covers ArcadiaGame::debug_time
     */
    public function test_debug_time() {
        $game = new ArcadiaGame();

        $this->assertGreaterThan( 0, $game->debug_time() );
    }


}