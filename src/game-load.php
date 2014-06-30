<?php

require( GAME_PATH . 'include/game.php' );

require( GAME_PATH . 'include/achieve.php' );
require( GAME_PATH . 'include/common.php' );
require( GAME_PATH . 'include/db.php' );
require( GAME_PATH . 'include/mail.php' );
require( GAME_PATH . 'include/plugin.php' );
require( GAME_PATH . 'include/quest.php' );
require( GAME_PATH . 'include/user.php' );
require( GAME_PATH . 'include/zone.php' );

require( GAME_PATH . 'game-dashboard.php' );


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$GLOBALS[ 'game' ] = new Game();
$GLOBALS[ 'user' ] = game_user_logged_in();

$page_map = array(
    'account' => 'account.php',
);

$setting_map = array(
    'new_character' => 'user_create_character',
    'password' => 'user_change_password',
    'select_character' => 'user_select_character',
    'change_character' => 'user_clear_character',
    'quest_accept' => 'character_quest_accept',
    'quest_complete' => 'character_quest_complete',
    'buy_item' => 'character_buy_item',
    'sell_item' => 'character_sell_item',
);

$custom_setting_map = array();

if ( defined( 'GAME_CUSTOM_PATH' ) ) {
    require( GAME_CUSTOM_PATH . 'custom-core.php' );
}
