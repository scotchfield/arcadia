<?php

class TestArcadiaCommon extends PHPUnit_Framework_TestCase {

    /**
     * @covers ArcadiaCommon::__construct
     */
    public function test_common_constructor() {
        $component = new ArcadiaCommon();

        $this->assertTrue( isset( $component->game_nonce_life ) );
    }

    /**
     * @covers ArcadiaCommon::__construct
     */
    public function test_common_constructor_nonce_life_set() {
        global $ag;

        $component = new ArcadiaCommon( $ag_obj = $ag, $nonce_life = 30 );

        $this->assertEquals( 30, $component->game_nonce_life );
    }

    /**
     * @covers ArcadiaCommon::get_array_if_set
     */
    public function test_get_array_if_set_empty_default() {
        $component = new ArcadiaCommon();

        $array = array();

        $ret_val = $component->get_array_if_set( $array, 'test', -1 );

        $this->assertEquals( $ret_val, -1 );
    }

    /**
     * @covers ArcadiaCommon::get_array_if_set
     */
    public function test_get_array_if_set_non_array_parameter() {
        $component = new ArcadiaCommon();

        $array = FALSE;

        $ret_val = $component->get_array_if_set( $array, 'test', -1 );

        $this->assertEquals( $ret_val, -1 );
    }

    /**
     * @covers ArcadiaCommon::get_array_if_set
     */
    public function test_get_array_if_set_correct() {
        $component = new ArcadiaCommon();

        $k = 'test';
        $v = -1;

        $array = array( $k => $v );

        $ret_val = $component->get_array_if_set( $array, $k, $v );

        $this->assertEquals( $ret_val, $v );
    }

    /**
     * @covers ArcadiaCommon::get_bit
     */
    public function test_get_bit_zero() {
        $component = new ArcadiaCommon();

        $val = 0;

        $this->assertEquals( FALSE, $component->get_bit( $val, 0 ) );
    }

    /**
     * @covers ArcadiaCommon::get_bit
     */
    public function test_get_bit_one() {
        $component = new ArcadiaCommon();

        $val = 1;

        $this->assertEquals( TRUE, $component->get_bit( $val, 0 ) );
    }

    /**
     * @covers ArcadiaCommon::set_bit
     */
    public function test_set_bit_zero() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 1, $component->set_bit( 0, 0 ) );
    }

    /**
     * @covers ArcadiaCommon::set_bit
     */
    public function test_set_bit_one() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 2, $component->set_bit( 0, 1 ) );
    }

   /**
     * @covers ArcadiaCommon::set_bit
     */
    public function test_set_bit_two() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 4, $component->set_bit( 0, 2 ) );
    }

    /**
     * @covers ArcadiaCommon::set_bit
     */
    public function test_set_bit_negative() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 0, $component->set_bit( 0, -1 ) );
    }

    /**
     * @covers ArcadiaCommon::bit_count
     */
    public function test_bit_count_zero() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 0, $component->bit_count( 0 ) );
    }

    /**
     * @covers ArcadiaCommon::bit_count
     */
    public function test_bit_count_one() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 1, $component->bit_count( 1 ) );
    }

    /**
     * @covers ArcadiaCommon::bit_count
     */
    public function test_bit_count_ten() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 10, $component->bit_count( 1023 ) );
    }

    /**
     * @covers ArcadiaCommon::random_string
     */
    public function test_random_string_length_zero() {
        $component = new ArcadiaCommon();

        $n = 0;
        $st = $component->random_string( $n );

        $this->assertEquals( $n, strlen( $st ) );
    }

    /**
     * @covers ArcadiaCommon::random_string
     */
    public function test_random_string_length() {
        $component = new ArcadiaCommon();

        $n = 10;
        $st = $component->random_string( $n );

        $this->assertEquals( $n, strlen( $st ) );
    }

    /**
     * @covers ArcadiaCommon::nonce_tick
     */
    public function test_nonce_tick_gt_zero() {
        $component = new ArcadiaCommon();

        $nonce_tick = $component->nonce_tick();

        $this->assertGreaterThan( 0, $nonce_tick );
    }

    /**
     * @covers ArcadiaCommon::nonce_verify
     */
    public function test_nonce_verify_no_char() {
        $component = new ArcadiaCommon();

        $result = $component->nonce_verify( '' );

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaCommon::nonce_verify
     */
    public function test_nonce_verify_char_invalid_nonce() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaCommon();

        $result = $component->nonce_verify( '' );

        $ag->char = FALSE;

        $this->assertFalse( $result );
    }

    /**
     * @covers ArcadiaCommon::nonce_verify
     */
    public function test_nonce_verify_char_valid_nonce() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaCommon();

        $nonce = $component->nonce_create();
        $result = $component->nonce_verify( $nonce );

        $ag->char = FALSE;

        $this->assertTrue( $result );
    }

    /**
     * @covers ArcadiaCommon::nonce_verify
     */
    public function test_nonce_verify_char_valid_nonce_in_past() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaCommon();

        $nonce = $component->nonce_create( $state = -1,
            $time = time() - $component->game_nonce_life / 2 );
        $result = $component->nonce_verify( $nonce );

        $ag->char = FALSE;

        $this->assertTrue( $result );
    }

    /**
     * @covers ArcadiaCommon::nonce_create
     */
    public function test_nonce_create_no_char() {
        $component = new ArcadiaCommon();

        $nonce = $component->nonce_create();

        $this->assertFalse( $nonce );
    }

    /**
     * @covers ArcadiaCommon::nonce_create
     */
    public function test_nonce_create_basic() {
        global $ag;

        $ag->char = array( 'id' => 1 );

        $component = new ArcadiaCommon();

        $nonce = $component->nonce_create();

        $ag->char = FALSE;

        $this->assertNotFalse( $nonce );
    }

    /**
     * @covers ArcadiaCommon::number_with_suffix
     */
    public function test_number_with_suffix_zero() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '0th', $component->number_with_suffix( 0 ) );
    }

    /**
     * @covers ArcadiaCommon::number_with_suffix
     */
    public function test_number_with_suffix_one() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '1st', $component->number_with_suffix( 1 ) );
    }

    /**
     * @covers ArcadiaCommon::number_with_suffix
     */
    public function test_number_with_suffix_two() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '2nd', $component->number_with_suffix( 2 ) );
    }

    /**
     * @covers ArcadiaCommon::number_with_suffix
     */
    public function test_number_with_suffix_three() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '3rd', $component->number_with_suffix( 3 ) );
    }

    /**
     * @covers ArcadiaCommon::number_with_suffix
     */
    public function test_number_with_suffix_eleven() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '11th', $component->number_with_suffix( 11 ) );
    }

    /**
     * @covers ArcadiaCommon::get_if_plural
     */
    public function test_get_if_plural_no() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 'word', $component->get_if_plural( 1, 'word' ) );
    }

    /**
     * @covers ArcadiaCommon::get_if_plural
     */
    public function test_get_if_plural_yes() {
        $component = new ArcadiaCommon();

        $this->assertEquals( 'words', $component->get_if_plural( 2, 'word' ) );
    }

    /**
     * @covers ArcadiaCommon::time_round
     */
    public function test_time_round_negative() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '', $component->time_round( -1 ) );
    }

    /**
     * @covers ArcadiaCommon::time_round
     */
    public function test_time_round_zero() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '0 seconds', $component->time_round( 0 ) );
    }

    /**
     * @covers ArcadiaCommon::time_round
     */
    public function test_time_round_one() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '1 second', $component->time_round( 1 ) );
    }

    /**
     * @covers ArcadiaCommon::time_round
     */
    public function test_time_round_minute() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '1 minute', $component->time_round( 60 ) );
    }

    /**
     * @covers ArcadiaCommon::time_round
     */
    public function test_time_round_hour() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '1 hour', $component->time_round( 60 * 60 ) );
    }

    /**
     * @covers ArcadiaCommon::time_round
     */
    public function test_time_round_day() {
        $component = new ArcadiaCommon();

        $this->assertEquals( '1 day', $component->time_round( 60 * 60 * 24 ) );
    }

    /**
     * @covers ArcadiaCommon::time_expand
     */
    public function test_time_expand_zero() {
        $component = new ArcadiaCommon();

        $this->assertEquals( $component->time_expand( 0 ), '' );
    }

    /**
     * @covers ArcadiaCommon::time_expand
     */
    public function test_time_expand_full() {
        $component = new ArcadiaCommon();

        $this->assertEquals( $component->time_expand( 1209599 ),
            '1 week, 6 days, 23 hours, 59 minutes, 59 seconds' );
    }

    /**
     * @covers ArcadiaCommon::time_expand
     */
    public function test_time_expand_negative() {
        $component = new ArcadiaCommon();

        $this->assertEquals( $component->time_expand( -1 ), '' );
    }

    /**
     * @covers ArcadiaCommon::rand_float
     */
    public function test_rand_float_in_range() {
        $component = new ArcadiaCommon();

        $rand_min = 0;
        $rand_max = 100;

        $result = $component->rand_float( $rand_min, $rand_max );

        $this->assertGreaterThanOrEqual( $rand_min, $result );
        $this->assertLessThanOrEqual( $rand_max, $result );
    }

}
