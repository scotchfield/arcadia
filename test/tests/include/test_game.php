<?php

class TestArcadiaGame extends PHPUnit_Framework_TestCase {

    /**
     * @covers Arcadia_Game::get_action
     * @covers Arcadia_Game::set_action
     */
    public function test_game_set_action() {
        $game = new Arcadia_Game();

    	$original = $game->get_action();

    	$test = 'test_action';
        $game->set_action( $test );

    	$result = $game->get_action();

    	$this->assertNotEquals( $original, $result );
    	$this->assertEquals( $test, $result );
    }

}