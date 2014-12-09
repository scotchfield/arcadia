<?php

class TestArcadiaGame extends PHPUnit_Framework_TestCase {

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

}