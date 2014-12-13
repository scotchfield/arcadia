<?php

require( GAME_PATH . 'include/game.php' );
require( GAME_PATH . 'include/game_meta.php' );
require( GAME_PATH . 'include/plugin.php' );
require( GAME_PATH . 'include/component.php' );

require( GAME_PATH . 'include/achieve.php' );
require( GAME_PATH . 'include/buff.php' );
require( GAME_PATH . 'include/common.php' );
require( GAME_PATH . 'include/cron.php' );
require( GAME_PATH . 'include/db.php' );
require( GAME_PATH . 'include/heartbeat.php' );
require( GAME_PATH . 'include/inventory.php' );
require( GAME_PATH . 'include/item.php' );
require( GAME_PATH . 'include/log.php' );
require( GAME_PATH . 'include/login.php' );
require( GAME_PATH . 'include/mail.php' );
require( GAME_PATH . 'include/npc.php' );
require( GAME_PATH . 'include/quest.php' );
require( GAME_PATH . 'include/tracking.php' );
require( GAME_PATH . 'include/user.php' );
require( GAME_PATH . 'include/zone.php' );


if ( function_exists( 'session_status' ) ) {
    if ( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }
} else {
    if ( session_id() == '' ) {
        session_start();
    }
}

global $ag;

$ag = new ArcadiaGame();

$ag->set_component( 'common', new ArcadiaCommon() );
$ag->set_component( 'db', new ArcadiaDb() );

$ag->set_logger( new ArcadiaLog() );

$ag->user = game_user_logged_in();

$page_map = array(
    'account' => 'account.php',
);

add_state( 'do_setting', 'new_character', 'user_create_character' );
add_state( 'do_setting', 'password', 'user_change_password' );
add_state( 'do_setting', 'select_character', 'user_select_character' );
add_state( 'do_setting', 'change_character', 'user_clear_character' );

if ( defined( 'GAME_CUSTOM_PATH' ) ) {
    require( GAME_CUSTOM_PATH . 'custom-core.php' );
}
