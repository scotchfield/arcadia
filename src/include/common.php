<?php

if ( ! defined( 'game_nonce_life' ) ) {
    define( 'game_nonce_life', 60 );
}

define( 'SECONDS_WEEK',    60 * 60 * 24 * 7 );
define( 'SECONDS_DAY',     60 * 60 * 24 );
define( 'SECONDS_HOUR',    60 * 60 );
define( 'SECONDS_MINUTE',  60 );

function get_array_if_set( $array, $key, $default ) {
    if ( isset( $array[ $key ] ) ) {
        return $array[ $key ];
    }
    return $default;
}

function get_bit( $val, $bit ) {
    if ( $val & ( 1 << $bit ) ) {
        return TRUE;
    }
    return FALSE;
}

function set_bit( $val, $bit ) {
    if ( $bit < 0 ) {
        return $val;
    }

    return $val | ( 1 << $bit );
}

function bit_count( $val ) {
    $v = ( int ) $val;
    $c = 0;

    for ( $c = 0; $v; $c++ ) {
        $v &= $v - 1;
    }

    return $c;
}

function random_string( $length ) {
    $st = '';
    $values = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    for ( $i = 0; $i < $length; $i++ ) {
        $st = $st . $values[ mt_rand( 0, strlen( $values ) - 1 ) ];
    }
    return $st;
}

function nonce_tick( $use_time = FALSE ) {
    if ( FALSE == $use_time ) {
        $use_time = time();
    }
    return ceil( $use_time / ( game_nonce_life / 2 ) );
}

function nonce_verify( $nonce, $state = -1 ) {
    global $ag;

    if ( FALSE == $ag->char ) {
        return FALSE;
    }

    $tick = nonce_tick();
    $c_id = intval( $ag->char[ 'id' ] );

    if ( $nonce === substr( md5( $tick . $state . $c_id ), 0, 10 ) ) {
        return TRUE;
    }

    if ( $nonce === substr( md5( ( $tick - 1 ) . $state . $c_id ),
                            0, 10 ) ) {
        return TRUE;
    }

    return FALSE;
}

function nonce_create( $state = -1, $time = FALSE ) {
    global $ag;

    if ( FALSE == $ag->char ) {
        return FALSE;
    }

    $tick = nonce_tick( $use_time = $time );
    $c_id = intval( $ag->char[ 'id' ] );

    return substr( md5( $tick . $state . $c_id ), 0, 10 );
}

function number_with_suffix( $n ) {
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

function get_if_plural( $n, $word ) {
    if ( 1 != $n ) {
        return $word . 's';
    }

    return $word;
}

function time_round( $time ) {
    if ( $time < 0 ) {
        return '';
    } else if ( $time < 60 ) {
        return $time . ' ' . get_if_plural( $time, 'second' );
    } else if ( $time < 60 * 60 ) {
        $t = floor( $time / 60 );
        return $t . ' ' . get_if_plural( $t, 'minute' );
    } else if ( $time < 60 * 60 * 24 ) {
        $t = floor( $time / ( 60 * 60 ) );
        return $t . ' ' . get_if_plural( $t, 'hour' );
    } else {
        $t = floor( $time / ( 60 * 60 * 24 ) );
        return $t . ' ' . get_if_plural( $t, 'day' );
    }
}

function time_expand( $time ) {
    $st_obj = array();
    $time_obj = array(
        array( SECONDS_WEEK, 'week' ),
        array( SECONDS_DAY, 'day' ),
        array( SECONDS_HOUR, 'hour' ),
        array( SECONDS_MINUTE, 'minute' ),
        array( 1, 'second' ),
    );

    foreach ( $time_obj as $t ) {
        if ( $time >= $t[ 0 ] ) {
            $n = floor( $time / $t[ 0 ] );
            $st_obj[] = $n . ' ' . get_if_plural( $n, $t[ 1 ] );
            $time -= $n * $t[ 0 ];
        }
    }

    return implode( ', ', $st_obj );
}

function rand_float( $f_min, $f_max ) {
    $f_min = floatval( $f_min );
    $f_max = floatval( $f_max );

    return $f_min + ( mt_rand() / mt_getrandmax() ) * ( $f_max - $f_min );
}