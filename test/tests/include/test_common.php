<?php

class TestArcadiaCommon extends PHPUnit_Framework_TestCase {
    public function test_get_array_if_set_empty_default() {
        $array = array();

        $ret_val = get_array_if_set( $array, 'test', -1 );

	$this->assertEquals( $ret_val, -1 );
    }
}
