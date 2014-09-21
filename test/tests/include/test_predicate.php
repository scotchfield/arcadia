<?php

class TestArcadiaPredicate extends PHPUnit_Framework_TestCase {

    /**
     * @covers ::eval_predicate
     */
    public function test_eval_predicate_none() {
        $result = eval_predicate( FALSE, array() );

        $this->assertFalse( $result );
    }

    /**
     * @covers ::eval_function
     */
    public function test_eval_function_none() {
        $result = eval_function( FALSE, array() );

        $this->assertFalse( $result );
    }

}
