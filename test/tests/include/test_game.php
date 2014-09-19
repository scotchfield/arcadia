<?php

class TestArcadiaGame extends PHPUnit_Framework_TestCase {

    /**
     * @covers ::game_set_action
     * @covers ::game_get_action
     */
    public function test_game_set_action() {
    	$original = game_get_action();

    	$test = 'test_action';
    	game_set_action( $test );

    	$result = game_get_action();

    	$this->assertNotEquals( $original, $result );
    	$this->assertEquals( $test, $result );
    }

}