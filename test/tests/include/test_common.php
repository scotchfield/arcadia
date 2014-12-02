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
     * @covers ::nonce_tick
     */
    public function test_nonce_tick_gt_zero() {
        $nonce_tick = nonce_tick();

        $this->assertGreaterThan( 0, $nonce_tick );
    }

    /**
     * @covers ::nonce_verify
     */
    public function test_nonce_verify_no_char() {
        $result = nonce_verify( '' );

        $this->assertFalse( $result );
    }

    /**
     * @covers ::nonce_verify
     */
    public function test_nonce_verify_char_invalid_nonce() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $result = nonce_verify( '' );

        $ag->char = FALSE;

        $this->assertFalse( $result );
    }

    /**
     * @covers ::nonce_verify
     */
    public function test_nonce_verify_char_valid_nonce() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $nonce = nonce_create();
        $result = nonce_verify( $nonce );

        $ag->char = FALSE;

        $this->assertTrue( $result );
    }

    /**
     * @covers ::nonce_verify
     */
    public function test_nonce_verify_char_valid_nonce_in_past() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $nonce = nonce_create( $state = -1,
            $time = time() - game_nonce_life / 2 );
        $result = nonce_verify( $nonce );

        $ag->char = FALSE;

        $this->assertTrue( $result );
    }

    /**
     * @covers ::nonce_create
     */
    public function test_nonce_create_no_char() {
        $nonce = nonce_create();

        $this->assertFalse( $nonce );
    }

    /**
     * @covers ::nonce_create
     */
    public function test_nonce_create_basic() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $nonce = nonce_create();

        $ag->char = FALSE;

        $this->assertNotFalse( $nonce );
    }

    /**
     * @covers ::number_with_suffix
     */
    public function test_number_with_suffix_zero() {
        $this->assertEquals( '0th', number_with_suffix( 0 ) );
    }

    /**
     * @covers ::number_with_suffix
     */
    public function test_number_with_suffix_one() {
        $this->assertEquals( '1st', number_with_suffix( 1 ) );
    }

    /**
     * @covers ::number_with_suffix
     */
    public function test_number_with_suffix_two() {
        $this->assertEquals( '2nd', number_with_suffix( 2 ) );
    }

    /**
     * @covers ::number_with_suffix
     */
    public function test_number_with_suffix_three() {
        $this->assertEquals( '3rd', number_with_suffix( 3 ) );
    }

    /**
     * @covers ::number_with_suffix
     */
    public function test_number_with_suffix_eleven() {
        $this->assertEquals( '11th', number_with_suffix( 11 ) );
    }

    /**
     * @covers ::get_if_plural
     */
    public function test_get_if_plural_no() {
        $this->assertEquals( 'word', get_if_plural( 1, 'word' ) );
    }

    /**
     * @covers ::get_if_plural
     */
    public function test_get_if_plural_yes() {
        $this->assertEquals( 'words', get_if_plural( 2, 'word' ) );
    }

    /**
     * @covers ::time_round
     */
    public function test_time_round_negative() {
        $this->assertEquals( '', time_round( -1 ) );
    }

    /**
     * @covers ::time_round
     */
    public function test_time_round_zero() {
        $this->assertEquals( '0 seconds', time_round( 0 ) );
    }

    /**
     * @covers ::time_round
     */
    public function test_time_round_one() {
        $this->assertEquals( '1 second', time_round( 1 ) );
    }

    /**
     * @covers ::time_round
     */
    public function test_time_round_minute() {
        $this->assertEquals( '1 minute', time_round( 60 ) );
    }

    /**
     * @covers ::time_round
     */
    public function test_time_round_hour() {
        $this->assertEquals( '1 hour', time_round( 60 * 60 ) );
    }

    /**
     * @covers ::time_round
     */
    public function test_time_round_day() {
        $this->assertEquals( '1 day', time_round( 60 * 60 * 24 ) );
    }

}
