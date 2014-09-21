<?php

class TestArcadiaPlugin extends PHPUnit_Framework_TestCase {

    /**
     * @covers ::do_action
     */
    public function test_do_action_none() {
        $this->assertNull( do_action( '' ) );
    }

}
