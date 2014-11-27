<?php

try {
    $GLOBALS[ 'game_db' ] = new PDO(
        'mysql:host=' . DB_ADDRESS . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER, DB_PASSWORD );
} catch ( PDOException $e ) {
    echo( "Warning: Database not found!\n" );
    die();
}

function db_fetch( $query, $args = array() ) {
    global $game_db;

    $stmt = $game_db->prepare( $query );
    $stmt->execute( $args );
    $obj = $stmt->fetch( PDO::FETCH_ASSOC );

    return $obj;
}

function db_fetch_all( $query, $args = array(), $key_assoc = FALSE ) {
    global $game_db;

    $stmt = $game_db->prepare( $query );
    $stmt->execute( $args );
    $obj = $stmt->fetchAll( PDO::FETCH_ASSOC );

    if ( FALSE != $key_assoc ) {
        $assoc_obj = array();
        foreach ( $obj as $o ) {
            $assoc_obj[ $o[ $key_assoc ] ] = $o;
        }
        $obj = $assoc_obj;
    }

    return $obj;
}

function db_execute( $query, $args = array() ) {
    global $game_db;

    $stmt = $game_db->prepare( $query );
    return $stmt->execute( $args );
}

function db_last_insert_id() {
    global $game_db;

    return $game_db->lastInsertId();
}

function db_begin_transaction() {
    global $game_db;

    $game_db->beginTransaction();
}

function db_commit() {
    global $game_db;

    $game_db->commit();
}

function db_rollback() {
    global $game_db;

    $game_db->rollBack();
}