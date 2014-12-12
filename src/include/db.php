<?php

class ArcadiaDb extends ArcadiaComponent {

    private $db = FALSE;

    public function __construct() {
        try {
            $this->db = new PDO(
                'mysql:host=' . DB_ADDRESS . ';dbname=' . DB_NAME .
                    ';charset=utf8',
                DB_USER, DB_PASSWORD );
        } catch ( PDOException $e ) {
            echo( "Warning: Database not found!\n" );
            die();
        }
    }

    public function db_fetch( $query, $args = array() ) {
        $stmt = $this->db->prepare( $query );
        $stmt->execute( $args );
        $obj = $stmt->fetch( PDO::FETCH_ASSOC );

        return $obj;
    }

    public function db_fetch_all( $query, $args = array(),
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

    public function db_execute( $query, $args = array() ) {
        $stmt = $this->db->prepare( $query );
        return $stmt->execute( $args );
    }

    public function db_last_insert_id() {
        return $this->db->lastInsertId();
    }

    public function db_begin_transaction() {
        return $this->db->beginTransaction();
    }

    public function db_commit() {
        return $this->db->commit();
    }

    public function db_rollback() {
        return $this->db->rollBack();
    }

}