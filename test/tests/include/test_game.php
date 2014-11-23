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

}