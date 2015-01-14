<?php

class ArcadiaDb extends ArcadiaComponent {

    private $db = FALSE;

    public function __construct( $addr, $name, $user, $pass ) {
        try {
            $this->db = new PDO(
                'mysql:host=' . $addr . ';dbname=' . $name .
                    ';charset=utf8',
                $user, $pass );
        } catch ( PDOException $e ) {
            echo( "Warning: Database not found!\n" );
        }
    }

    public function fetch( $query, $args = array() ) {
        $stmt = $this->db->prepare( $query );
        $stmt->execute( $args );
        $obj = $stmt->fetch( PDO::FETCH_ASSOC );

        return $obj;
    }

    public function fetch_all( $query, $args = array(),
                                  $key_assoc = FALSE ) {
        $stmt = $this->db->prepare( $query );
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

    public function execute( $query, $args = array() ) {
        $stmt = $this->db->prepare( $query );
        return $stmt->execute( $args );
    }

    public function last_insert_id() {
        return $this->db->lastInsertId();
    }

    public function begin_transaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollback() {
        return $this->db->rollBack();
    }

}