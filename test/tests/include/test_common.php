<?php

class TestArcadiaCommon extends PHPUnit_Framework_TestCase {
    public function test_get_array_if_set_empty_default() {
        $array = array();

        $ret_val = get_array_if_set( $array, 'test', -1 );

        $this->assertEquals( $ret_val, -1 );
    }

    public function test_get_array_if_set_non_array_parameter() {
        $array = FALSE;

        $ret_val = get_array_if_set( $array, 'test', -1 );

        $this->assertEquals( $ret_val, -1 );
    }

    public function test_get_array_if_set_correct() {
    	$k = 'test';
    	$v = -1;

        $array = array( $k => $v );

        $ret_val = get_array_if_set( $array, $k, $v );

        $this->assertEquals( $ret_val, $v );
    }

    public function test_get_bit_zero() {
    	$val = 0;

        $this->assertEquals( FALSE, get_bit( $val, 0 ) );
    }

    public function test_get_bit_one() {
    	$val = 1;

        $this->assertEquals( TRUE, get_bit( $val, 0 ) );
    }

}
