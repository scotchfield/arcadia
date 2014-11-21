<?php

class TestArcadiaGame extends PHPUnit_Framework_TestCase {

    /**
     * @covers Arcadia_Game::get_state
     * @covers Arcadia_Game::set_state
     */
    public function test_game_set_state() {
        $game = new Arcadia_Game();

    	$original = $game->get_state();

    	$test = 'test_state';
        $game->set_state( $test );

    	$result = $game->get_state();

    	$this->assertNotEquals( $original, $result );
    	$this->assertEquals( $test, $result );
    }

}