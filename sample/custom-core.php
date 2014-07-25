<?php

$custom_start_page = 'title.php';

define( 'sample_meta_type_character', 1 );

define( 'SAMPLE_CHARACTER_CREDITS',   1 );
define( 'SAMPLE_CHARACTER_TIP',       2 );


function sample_login() {
    global $character;

    ensure_character_meta( $character[ 'id' ], sample_meta_type_character,
                           SAMPLE_CHARACTER_CREDITS );
}

add_action( 'select_character', 'sample_login' );

function sample_header() {
    global $character;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo( GAME_NAME ); ?></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo( GAME_CUSTOM_STYLE_URL );
        ?>sample.css">
    <link href="http://fonts.googleapis.com/css?family=Raleway:400,500"
          rel="stylesheet" type="text/css">
  </head>
  <body>

<div class="container">

<?php
    if ( FALSE != $character ) {
?>
  <div class="row">
    <h1>Welcome, <?php echo( $character[ 'character_name' ] ); ?>.</h1>
  </div>
<?php
    }
}

function sample_footer() {
    global $character;
?>
  <div class="row">
    <ul class="list-inline">
      <li><a href="<?php echo( GAME_URL ); ?>">Home</a></li>
      <li><a href="?action=about">About</a></li>
      <li><a href="?action=contact">Contact</a></li>
      <li><a href="game-logout.php">Logout</a></li>
    </ul>
  </div>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

</body>
</html>
<?
}

add_action( 'game_header', 'sample_header' );
add_action( 'game_footer', 'sample_footer' );

function sample_validate_user( $args ) {
    if ( ! isset( $args[ 'user_id' ] ) ) {
        return;
    }

    set_user_max_characters( $args[ 'user_id' ], 1 );
}

add_action( 'validate_user', 'sample_validate_user' );

function sample_select_check() {
    global $character;

    if ( FALSE == $character ) {
        game_set_action( 'select' );
    }
}

add_action( 'action_set', 'sample_select_check' );

function sample_select_print() {
    global $user;

    if ( strcmp( 'select', game_get_action() ) ) {
       return;
    }

    $char_obj = get_characters_for_user( $user[ 'id' ] );
?>
<div class="row">
  <div class="col-md-3">
    &nbsp;
  </div>
  <div class="col-md-6">

<h1 class="text-center">Welcome back,
<?php echo( $user[ 'user_name' ] ); ?>.</h1>

<h2 class="text-center">Select a character:</h2>

<?php
    if ( count( $char_obj ) == 0 ) {
        echo( '<h3 class="text-center">None found!</h3>' );
    } else {
        foreach ( $char_obj as $char ) {
            echo( '<h3 class="text-center">' .
                  '<a href="game-setting.php?setting=select_character' .
                  '&amp;id=' . $char[ 'id' ] . '">' .
                  $char[ 'character_name' ] . '</a></h3>' );
        }
    }

    if ( count( $char_obj ) < $user[ 'max_characters' ] ) {
?>
<h1 class="text-center">Create a character</h1>
<form name="char_form" id="char_form" method="get" action="game-setting.php">
<div class="form-group">
<label>Character Name</label>
<input class="form-control" name="char_name" id="char_name" value="" type="text">
</div>
<button type="submit" class="btn btn-default">Let's go!</button>
<input type="hidden" name="setting" value="new_character">
</form>
<?php
    }
?>
  </div>
  <div class="col-md-3">

  </div>
</div>
<?php
}

add_action( 'do_page_content', 'sample_select_print' );

function sample_tip_print() {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    $tip = character_meta( sample_meta_type_character, SAMPLE_CHARACTER_TIP );

    if ( 0 < strlen( $tip ) ) {
        echo( $tip );
        update_character_meta( $character[ 'id' ], sample_meta_type_character,
            SAMPLE_CHARACTER_TIP, '' );
    }
}

add_action_priority( 'do_page_content', 'sample_tip_print' );

function sample_about() {
    if ( strcmp( 'about', game_get_action() ) ) {
       return;
    }

    echo( '<h1>This is a sample Arcadia project</h1>' );
}

function sample_contact() {
    if ( strcmp( 'contact', game_get_action() ) ) {
       return;
    }

    echo( '<h1><a href="https://github.com/scotchfield/arcadia">' .
          'https://github.com/scotchfield/arcadia</a></h1>' );
}

add_action( 'do_page_content', 'sample_about' );
add_action( 'do_page_content', 'sample_contact' );
