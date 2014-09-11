<?php

$GLOBALS[ 'game_action' ] = '';

function game_set_action( $action ) {
    $GLOBALS[ 'game_action' ] = $action;
}

function game_get_action() {
    return $GLOBALS[ 'game_action' ];
}
