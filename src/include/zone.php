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

