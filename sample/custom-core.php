<?php

require( GAME_CUSTOM_PATH . 'title.php' );

define( 'sample_meta_type_character', 1 );

define( 'SAMPLE_CHARACTER_CREDITS',   1 );
define( 'SAMPLE_CHARACTER_TIP',       2 );


function sample_default_state() {
    global $ag;

    if ( FALSE == $ag->user ) {
        $ag->set_state( 'title' );
    } else if ( FALSE == $ag->char ) {
        $ag->set_state( 'select' );
    } else {
        $ag->set_state( 'zone' );
    }
}

add_state( 'set_default_state', 'sample_default_state' );

function sample_login() {
    global $ag;

    ensure_character_meta( $ag->char[ 'id' ], sample_meta_type_character,
                           SAMPLE_CHARACTER_CREDITS );
}

add_state( 'select_character', 'sample_login' );

function sample_header() {
    global $ag;
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
    if ( FALSE != $ag->char ) {
?>
  <div class="row">
    <h1>Welcome, <?php echo( $ag->char[ 'character_name' ] ); ?>.</h1>
  </div>
<?php
    }
}

function sample_footer() {
    global $ag;
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

add_state( 'game_header', 'sample_header' );
add_state( 'game_footer', 'sample_footer' );

function sample_validate_user( $args ) {
    if ( ! isset( $args[ 'user_id' ] ) ) {
        return;
    }

    set_user_max_characters( $args[ 'user_id' ], 1 );
}

add_state( 'validate_user', 'sample_validate_user' );

function sample_select_print() {
    global $ag;

    if ( strcmp( 'select', $ag->get_state() ) ) {
       return;
    }

    $char_obj = get_characters_for_user( $ag->user[ 'id' ] );
?>
<div class="row">
  <div class="col-md-3">
    &nbsp;
  </div>
  <div class="col-md-6">

<h1 class="text-center">Welcome back,
<?php echo( $ag->user[ 'user_name' ] ); ?>.</h1>

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

    if ( count( $char_obj ) < $ag->user[ 'max_characters' ] ) {
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

add_state( 'do_page_content', 'sample_select_print' );

function sample_tip_print() {
    global $ag;

    if ( FALSE == $ag->char ) {
        return;
    }

    $tip = character_meta( sample_meta_type_character, SAMPLE_CHARACTER_TIP );

    if ( 0 < strlen( $tip ) ) {
        echo( $tip );
        update_character_meta( $ag->char[ 'id' ], sample_meta_type_character,
            SAMPLE_CHARACTER_TIP, '' );
    }
}

add_state_priority( 'do_page_content', 'sample_tip_print' );

function sample_about() {
    global $ag;

    if ( strcmp( 'about', $ag->get_state() ) ) {
       return;
    }

    echo( '<h1>This is a sample Arcadia project</h1>' );
}

function sample_contact() {
    global $ag;

    if ( strcmp( 'contact', $ag->get_state() ) ) {
       return;
    }

    echo( '<h1><a href="https://github.com/scotchfield/arcadia">' .
          'https://github.com/scotchfield/arcadia</a></h1>' );
}

add_state( 'do_page_content', 'sample_about' );
add_state( 'do_page_content', 'sample_contact' );
