<?php

require( dirname( __FILE__ ) . '/game-config.php' );

session_start();
$_SESSION = array();
session_destroy();

header( 'Location: ' . GAME_URL );

?> 