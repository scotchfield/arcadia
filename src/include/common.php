<?php

class ArcadiaCommon extends ArcadiaComponent {

    public $ag;
    public $game_nonce_life = 60;

    public function __construct( $ag_obj = FALSE, $nonce_life = FALSE ) {
        if ( $ag_obj ) {
            $this->ag = $ag_obj;
        } else {
            global $ag;

            $this->ag = $ag;
        }

        if ( $nonce_life ) {
            $this->game_nonce_life = $nonce_life;
        }
    }

    public function get_array_if_set( $array, $key, $default ) {
        if ( isset( $array[ $key ] ) ) {
            return $array[ $key ];
        }
        return $default;
    }

    public function get_bit( $val, $bit ) {
        if ( $val & ( 1 << $bit ) ) {
            return TRUE;
        }
        return FALSE;
    }

    public function set_bit( $val, $bit ) {
        if ( $bit < 0 ) {
            return $val;
        }

        return $val | ( 1 << $bit );
    }

    public function bit_count( $val ) {
        $v = ( int ) $val;
        $c = 0;

        for ( $c = 0; $v; $c++ ) {
            $v &= $v - 1;
        }

        return $c;
    }

    public function random_string( $length ) {
        $st = '';
        $values = 'abcdefghijklmnopqrstuvwxyz' .
                  'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        for ( $i = 0; $i < $length; $i++ ) {
            $st = $st . $values[ mt_rand( 0, strlen( $values ) - 1 ) ];
        }
        return $st;
    }

    public function nonce_tick( $use_time = FALSE ) {
        if ( FALSE == $use_time ) {
            $use_time = time();
        }
        return ceil( $use_time / ( $this->game_nonce_life / 2 ) );
    }

    public function nonce_verify( $nonce, $state = -1 ) {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        $tick = $this->nonce_tick();
        $c_id = intval( $this->ag->char[ 'id' ] );

        if ( $nonce === substr( md5( $tick . $state . $c_id ), 0, 10 ) ) {
            return TRUE;
        }

        if ( $nonce === substr( md5( ( $tick - 1 ) . $state . $c_id ),
                                0, 10 ) ) {
            return TRUE;
        }

        return FALSE;
    }

    public function nonce_create( $state = -1, $time = FALSE ) {
        if ( FALSE == $this->ag->char ) {
            return FALSE;
        }

        $tick = $this->nonce_tick( $use_time = $time );
        $c_id = intval( $this->ag->char[ 'id' ] );

        return substr( md5( $tick . $state . $c_id ), 0, 10 );
    }

    public function number_with_suffix( $n ) {
        $n = intval( $n );

        $n_h = $n % 100;
        if ( ( 11 == $n_h ) || ( 12 == $n_h ) || ( 13 == $n_h ) ) {
            return $n . 'th';
        }

        $n_t = $n % 10;
        if ( 1 == $n_t ) {
            return $n . 'st';
        } else if ( 2 == $n_t ) {
            return $n . 'nd';
        } else if ( 3 == $n_t ) {
            return $n . 'rd';
        }

        return $n . 'th';
    }

    public function get_if_plural( $n, $word ) {
        if ( 1 != $n ) {
            return $word . 's';
        }

        return $word;
    }

    public function time_round( $time ) {
        if ( $time < 0 ) {
            return '';
        } else if ( $time < 60 ) {
            return $time . ' ' . $this->get_if_plural( $time, 'second' );
        } else if ( $time < 60 * 60 ) {
            $t = floor( $time / 60 );
            return $t . ' ' . $this->get_if_plural( $t, 'minute' );
        } else if ( $time < 60 * 60 * 24 ) {
            $t = floor( $time / ( 60 * 60 ) );
            return $t . ' ' . $this->get_if_plural( $t, 'hour' );
        } else {
            $t = floor( $time / ( 60 * 60 * 24 ) );
            return $t . ' ' . $this->get_if_plural( $t, 'day' );
        }
    }

    public function time_expand( $time ) {
        $st_obj = array();
        $time_obj = array(
            array( 60 * 60 * 24 * 7, 'week' ),
            array( 60 * 60 * 24, 'day' ),
            array( 60 * 60, 'hour' ),
            array( 60, 'minute' ),
            array( 1, 'second' ),
        );

        foreach ( $time_obj as $t ) {
            if ( $time >= $t[ 0 ] ) {
                $n = floor( $time / $t[ 0 ] );
                $st_obj[] = $n . ' ' . $this->get_if_plural( $n, $t[ 1 ] );
                $time -= $n * $t[ 0 ];
            }
        }

        return implode( ', ', $st_obj );
    }

    public function rand_float( $f_min, $f_max ) {
        $f_min = floatval( $f_min );
        $f_max = floatval( $f_max );

        return $f_min + ( mt_rand() / mt_getrandmax() ) * ( $f_max - $f_min );
    }

}
