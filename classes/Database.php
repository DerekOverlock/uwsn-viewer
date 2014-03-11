<?php
require_once __DIR__ . "/../config.inc.php";

final class Database {
    /**
     * @var mysqli $mysqli
     */
    private $mysqli = null;
    /**
     * @var mysqli_result $mysqli;
     */
    private $result = null;
    private $sql = null;
    private $success = null;
    private $error = null;
    private $errno = null;
    private $insert_id = null;
    private $num_affected_rows = null;

    public function __construct($tbl_name, $primary_key) {
        $this->tbl_name = $tbl_name;
        $this->primary_key = $primary_key;
    }

    private function connect() {
        $this->mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if($this->mysqli->connect_error) die("Cannot connect to DB: " . $this->mysqli->connect_error);
    }

    private function disconnect() {
        $this->mysqli->close();
        $this->mysqli = null;
    }

    public function sanitize($value) {
        if(!$this->mysqli) $this->connect();
        $sanitized_var = $this->mysqli->real_escape_string($value);
        $this->disconnect();
        return $sanitized_var;
    }

    public function query($sql) {
        if(!$this->mysqli) $this->connect();
        $this->success = false;
        $this->error = false;
        $this->errno = false;
        $this->sql = $sql;
        $this->insert_id = null;
        $this->num_affected_rows = null;
        $this->result = $this->mysqli->query($sql);
        if($this->result) {
            $this->success = true;
            $this->insert_id = $this->mysqli->insert_id;
            $this->num_affected_rows = $this->mysqli->affected_rows;
        } else {
            $this->error = $this->mysqli->error;
            $this->errno = $this->mysqli->errno;
        }
        $this->disconnect();
        return $this;
    }

    public function save($fields, $id = NULL) {
        if($id) {
            $sql = "UPDATE " .$this->getTableName();
        } else {
            $sql = "INSERT INTO ".$this->getTableName();
        }
        $sql .= " SET ";
        $tmp = array();
        foreach($fields as $key=>$value) {
            $tmp[] = "`$key`='".$this->sanitize($value)."'";
        }
        $sql .= implode(", ", $tmp);
        if($id)
            $sql .= " WHERE " . "`". $this->getPrimaryKey()."`="."'".$this->sanitize($id)."'";
        return $this->query($sql)->getQueryResult();
    }

    public function delete($id) {
        $sql = "DELETE FROM " . $this->getTableName() . " WHERE `".$this->getPrimaryKey()."`='$id''";
        return $this->query($sql)->getQueryResult();
    }

    public function getQueryResult() {
        $result = new stdClass;
        $result->success = $this->success;
        $result->error = $this->error;
        $result->errno = $this->errno;
        $result->sql = $this->sql;
        $result->result = $this->result;
        $result->insert_id = $this->insert_id;
        $result->num_affected_rows = $this->num_affected_rows;
        return $result;
    }

    /**
     * @return array(mysqli_object)
     */
    public function itemize($className = NULL) {
        $items = array();
        while($obj = $this->result->fetch_object($className)) {
            $items[] = $obj;
        }
        if(count($items) == 0) return false;
        return $items;
    }

    /**
     * @return String
     */
    protected function getTableName() {
        return $this->tbl_name;
    }

    /**
     * @return String
     */
    protected function getPrimaryKey() {
        return $this->primary_key;
    }
}

