<?php
require_once __DIR__ . "/../config.inc.php";

class DataModel {
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
    private $insert_id = null;

    private $tbl_name = null;
    private $primary_key = null;

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

    protected function query($sql) {
        if(!$this->mysqli) $this->connect();
        $this->success = false;
        $this->error = false;
        $this->sql = $sql;
        $this->insert_id = null;
        $this->result = $this->mysqli->query($sql);
        if($this->result) {
            $this->success = true;
            if($this->mysqli->insert_id > 0)
                $this->insert_id = $this->mysqli->insert_id;
        }
        else $this->error = $this->mysqli->error;
        $this->disconnect();
        return $this;
    }

    protected function save($fields, $id = NULL) {
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

    protected function getQueryResult() {
        $result = new stdClass;
        $result->success = $this->success;
        $result->error = $this->error;
        $result->sql = $this->sql;
        $result->result = $this->result;
        $result->insert_id = $this->insert_id;
        return $result;
    }

    /**
     * @param mysqli_result $result
     */
    protected function itemize() {
        $items = array();
        while($obj = $this->result->fetch_object()) {
            $items[] = $obj;
        }
        if(count($items) == 0) return false;
        return $items;
    }

    protected function getTableName() {
        return $this->tbl_name;
    }

    protected function getPrimaryKey() {
        return $this->primary_key;
    }
}

