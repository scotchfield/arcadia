<?php

class TestArcadiaPredicate extends PHPUnit_Framework_TestCase {

    public $result_predicate, $result_function;

    public function setUp() {
        global $valid_predicates, $valid_functions;

        $valid_predicates[ 'predicate_test' ] = array( $this, 'predicate_test' );
        $valid_functions[ 'function_test' ] = array( $this, 'function_test' );

        $this->result = FALSE;
    }

    public function tearDown() {
        global $valid_predicates, $valid_functions;

        unset( $valid_predicates[ 'predicate_test' ] );
        unset( $valid_functions[ 'function_test' ] );
    }

    public function predicate_test() {
        $this->result_predicate = TRUE;

        return TRUE;
    }

    public function function_test() {
        $this->result_function = TRUE;

        return TRUE;
    }

    /**
     * @covers ::eval_predicate
     */
    public function test_eval_predicate_none() {
        $result = eval_predicate( FALSE, array() );

        $this->assertFalse( $result );
    }

    /**
     * @covers ::eval_predicate
     */
    public function test_eval_predicate_simple() {
        $result = eval_predicate( 'predicate_test', array() );

        $this->assertTrue( $result );
        $this->assertTrue( $this->result_predicate );
    }

    /**
     * @covers ::eval_function
     */
    public function test_eval_function_none() {
        $result = eval_function( FALSE, array() );

        $this->assertFalse( $result );
    }

    /**
     * @covers ::eval_function
     */
    public function test_eval_function_simple() {
        $result = eval_function( 'function_test', array() );

        $this->assertTrue( $result );
        $this->assertTrue( $this->result_function );
    }

}
