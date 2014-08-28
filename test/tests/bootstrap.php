<?php

// game-config.php options for custom testing environment
define( 'GAME_NAME', 'Arcadia Testing' );
define( 'GAME_EMAIL', 'scott@scootah.com' );
define( 'GAME_PATH', '../src/' );
define( 'GAME_URL', 'http://localhost:8888/arcadia/src/' );

define( 'DB_ADDRESS', 'localhost' );
define( 'DB_PORT', 8889 );
define( 'DB_NAME', 'game_test' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );


// load the arcadia environment
require( GAME_PATH . 'game-load.php' );

