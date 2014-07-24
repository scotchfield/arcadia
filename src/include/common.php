<?php

if ( ! defined( 'game_nonce_life' ) ) {
    define( 'game_nonce_life', 60 );
}

define( 'SECONDS_WEEK',    60 * 60 * 24 * 7 );
define( 'SECONDS_DAY',     60 * 60 * 24 );
define( 'SECONDS_HOUR',    60 * 60 );
define( 'SECONDS_MINUTE',  60 );

function debug_print( $x ) {
    if ( is_array( $x ) ) {
        echo '<p>';
        print_r( $x );
        echo '</p>';
    } else {
        echo '<p>' . $x . '</p>';
    }
}

function debug_time() {
    list( $usec, $sec ) = explode( ' ', microtime() );
    return ( ( float ) $usec + ( float ) $sec );
}

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

function micro_time() {
    list( $usec, $sec ) = explode( ' ', microtime() );
    return ( ( float ) $usec + ( float ) $sec );
}

function random_string( $length ) {
    $st = '';
    $values = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    for ( $i = 0; $i < $length; $i++ ) {
        $st = $st . $values[ mt_rand( 0, strlen( $values ) - 1 ) ];
    }
    return $st;
}

function explode_meta( $s ) {
    if ( 0 == strlen( $s ) ) {
        return array();
    }

    $meta_obj = array();
    $s_obj = explode( ';', $s );
    foreach ( $s_obj as $x ) {
        $x = explode( '=', $x );
        $meta_obj[ $x[ 0 ] ] = $x[ 1 ];
    }
    return $meta_obj;
}

function explode_meta_nokey( $s ) {
    if ( 0 == strlen( $s ) ) {
        return array();
    }

    $meta_obj = array();
    $s_obj = explode( ';', $s );
    foreach ( $s_obj as $x ) {
        $x = explode( '=', $x );
        $meta_obj[] = array( $x[ 0 ], explode( ',', $x[ 1 ] ) );
    }
    return $meta_obj;
}

function nonce_tick() {
    return ceil( time() / ( game_nonce_life / 2 ) );
}

function nonce_verify( $nonce, $action = -1 ) {
    global $character;

    if ( FALSE == $character ) {
        return FALSE;
    }

    $tick = nonce_tick();
    $c_id = intval( $character[ 'id' ] );

    if ( $nonce === substr( md5( $tick . $action . $c_id ), 0, 10 ) ) {
        return TRUE;
    }

    if ( $nonce === substr( md5( ( $tick - 1 ) . $action . $c_id ),
                            0, 10 ) ) {
        return TRUE;
    }

    return false;
}

function nonce_create( $action = -1 ) {
    global $character;

    if ( FALSE == $character ) {
        return FALSE;
    }

    $tick = nonce_tick();
    $c_id = intval( $character[ 'id' ] );

    return substr( md5( $tick . $action . $c_id ), 0, 10 );
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

    return join( ', ', $st_obj );
}

function get_item( $id ) {
    return db_fetch( 'SELECT * FROM items WHERE id=?', array( $id ) );
}

function rand_float( $f_min, $f_max ) {
    $f_min = floatval( $f_min );
    $f_max = floatval( $f_max );

    return $f_min + ( mt_rand() / mt_getrandmax() ) * ( $f_max - $f_min );
}