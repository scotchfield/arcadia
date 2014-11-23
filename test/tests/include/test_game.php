<?php

class TestArcadiaGame extends PHPUnit_Framework_TestCase {

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
     * @covers ArcadiaGame::get_state_arg
     * @covers ArcadiaGame::set_state_arg
     */
    public function test_game_set_and_get_state_arg() {
        $game = new ArcadiaGame();

        $arg_k = 'test_k';
        $arg_v = 'test_v';

        $game->set_state_arg( $arg_k, $arg_v );

        $this->assertEquals( $arg_v, $game->get_state_arg( $arg_k ) );
    }

    /**
     * @covers ArcadiaGame::get_state_arg
     */
    public function test_game_get_state_arg_not_set() {
        $game = new ArcadiaGame();

        $this->assertFalse( $game->get_state_arg( 'test_not_set' ) );
    }

}