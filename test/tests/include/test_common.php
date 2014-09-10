<?php

class TestArcadiaCommon extends PHPUnit_Framework_TestCase {

    /**
     * @covers ::get_array_if_set
     */
    public function test_get_array_if_set_empty_default() {
        $array = array();

        $ret_val = get_array_if_set( $array, 'test', -1 );

        $this->assertEquals( $ret_val, -1 );
    }

    /**
     * @covers ::get_array_if_set
     */
    public function test_get_array_if_set_non_array_parameter() {
        $array = FALSE;

        $ret_val = get_array_if_set( $array, 'test', -1 );

        $this->assertEquals( $ret_val, -1 );
    }

    /**
     * @covers ::get_array_if_set
     */
    public function test_get_array_if_set_correct() {
        $k = 'test';
        $v = -1;

        $array = array( $k => $v );

        $ret_val = get_array_if_set( $array, $k, $v );

        $this->assertEquals( $ret_val, $v );
    }

    /**
     * @covers ::get_bit
     */
    public function test_get_bit_zero() {
        $val = 0;

        $this->assertEquals( FALSE, get_bit( $val, 0 ) );
    }

    /**
     * @covers ::get_bit
     */
    public function test_get_bit_one() {
        $val = 1;

        $this->assertEquals( TRUE, get_bit( $val, 0 ) );
    }

    /**
     * @covers ::set_bit
     */
    public function test_set_bit_zero() {
        $this->assertEquals( 1, set_bit( 0, 0 ) );
    }

    /**
     * @covers ::set_bit
     */
    public function test_set_bit_one() {
        $this->assertEquals( 2, set_bit( 0, 1 ) );
    }

   /**
     * @covers ::set_bit
     */
    public function test_set_bit_two() {
        $this->assertEquals( 4, set_bit( 0, 2 ) );
    }

    /**
     * @covers ::set_bit
     */
    public function test_set_bit_negative() {
        $this->assertEquals( 0, set_bit( 0, -1 ) );
    }

    /**
     * @covers ::bit_count
     */
    public function test_bit_count_zero() {
        $this->assertEquals( 0, bit_count( 0 ) );
    }

    /**
     * @covers ::bit_count
     */
    public function test_bit_count_one() {
        $this->assertEquals( 1, bit_count( 1 ) );
    }

    /**
     * @covers ::bit_count
     */
    public function test_bit_count_ten() {
        $this->assertEquals( 10, bit_count( 1023 ) );
    }

    /**
     * @covers ::random_string
     */
    public function test_random_string_length_zero() {
        $n = 0;
        $st = random_string( $n );

        $this->assertEquals( $n, strlen( $st ) );
    }

    /**
     * @covers ::random_string
     */
    public function test_random_string_length() {
        $n = 10;
        $st = random_string( $n );

        $this->assertEquals( $n, strlen( $st ) );
    }

    /**
     * @covers ::explode_meta
     */
    public function test_explode_meta_empty() {
        $meta = '';

        $meta_obj = explode_meta( $meta );

        $this->assertEmpty( $meta_obj );
    }

    /**
     * @covers ::explode_meta
     */
    public function test_explode_meta_single() {
        $meta = 'a=b';

        $meta_obj = explode_meta( $meta );

        $this->assertArrayHasKey( 'a', $meta_obj );
        $this->assertEquals( 'b', $meta_obj[ 'a' ] );
    }

    /**
     * @covers ::explode_meta
     */
    public function test_explode_meta_double() {
        $meta = 'a=b;c=d';

        $meta_obj = explode_meta( $meta );

        $this->assertArrayHasKey( 'a', $meta_obj );
        $this->assertEquals( 'b', $meta_obj[ 'a' ] );

        $this->assertArrayHasKey( 'c', $meta_obj );
        $this->assertEquals( 'd', $meta_obj[ 'c' ] );
    }

    /**
     * @covers ::explode_meta_nokey
     */
    public function test_explode_meta_nokey_empty() {
        $meta = '';

        $meta_obj = explode_meta_nokey( $meta );

        $this->assertEmpty( $meta_obj );
    }

    /**
     * @covers ::explode_meta_nokey
     */
    public function test_explode_meta_nokey_single() {
        $meta = 'a=b,c,d';

        $meta_obj = explode_meta_nokey( $meta );

        $this->assertEquals( array( 'a', array( 'b', 'c', 'd' ) ), $meta_obj[ 0 ] );
    }

    /**
     * @covers ::nonce_tick
     */
    public function test_nonce_tick_gt_zero() {
        $nonce_tick = nonce_tick();

        $this->assertGreaterThan( 0, $nonce_tick );
    }

}