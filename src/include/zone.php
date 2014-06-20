<?php

function get_zone( $id ) {
    return db_fetch( 'SELECT * FROM zones WHERE id=?', array( $id ) );
}

function get_zone_by_tag( $tag ) {
    return db_fetch( 'SELECT * FROM zones WHERE zone_tag=?', array( $tag ) );
}

function get_zone_transitions( $id ) {
    return db_fetch_all(
        'SELECT zt.*, z.zone_tag, z.zone_title, z.zone_type ' .
            'FROM zone_transitions AS zt, zones AS z ' .
            'WHERE z.id=zt.zone_destination AND zone_source=?',
        array( $id ) );
}

function get_zone_item( $zone_id, $item_id ) {
    return db_fetch(
        'SELECT * FROM zone_items WHERE zone_id=? AND item_id=?',
        array( $zone_id, $item_id ) );
}

function get_zone_item_full( $zone_id, $item_id ) {
    return db_fetch(
        'SELECT i.*, zi.state_meta FROM items AS i, zone_items AS zi ' .
            'WHERE zi.zone_id=? AND i.id=zi.item_id AND zi.item_id=?',
        array( $zone_id, $item_id ) );
}

function get_zone_items( $id ) {
    return db_fetch_all(
        'SELECT * FROM zone_items WHERE zone_id=?', array( $id ) );
}

function get_zone_items_full( $id ) {
    return db_fetch_all(
        'SELECT i.*, zi.state_meta FROM items AS i, zone_items as zi ' .
            'WHERE zi.zone_id=? AND i.id=zi.item_id',
        array( $id ) );
}