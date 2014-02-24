<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Database.php";

class NodeReading {
    private static $tbl_name = "NodeReading";
    private static $primary_key = "NodeReadID";

    private $model;
    private $current;
    private $temp;
    /**
     * @var DateTime
     */
    private $timestamp;
    private $node_id;
    private $nr_id;

    public function __construct($current, $temp, $timestamp, $node_id, $nr_id = NULL) {
        $this->model = self::getDatabase();
        $this->current = $current;
        $this->temp = $temp;
        $this->timestamp = $timestamp;
        $this->node_id = $node_id;
        if($nr_id) $this->nr_id = $nr_id;
    }

    public function save() {
        $fields = array(
            "Current" => $this->current(),
            "Temp" => $this->temp(),
            "Timestamp" => $this->timestamp()->format("YYYY-mm-dd H:i:s"),
            "NodeID" => $this->node_id()
        );
        $result = $this->model->save($fields, $this->nr_id);
        if($result->success && !$this->nr_id) {
            $this->nr_id = $result->insert_id;
        }
        return $result;
    }

    public function current($current = NULL) {
        if($current) {
            $this->current = $current;
            return $this;
        } else {
            return $this->current;
        }
    }

    public function temp($temp = NULL) {
        if($temp) {
            $this->temp = $temp;
            return $this;
        } else {
            return $this->temp;
        }
    }

    public function timestamp($timestamp = NULL) {
        if($timestamp) {
            $this->timestamp = $timestamp;
            return $this;
        } else {
            return $this->timestamp;
        }
    }

    public function node_id($node_id = NULL) {
        if($node_id) {
            $this->node_id = $node_id;
            return $this;
        } else {
            return $this->node_id;
        }
    }

    static public function getNodeReadings($node_id) {
        $db = self::getDatabase();
        $node_id = $db->sanitize($node_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeID = '".$node_id."'";
        $result = $db->query($sql)->itemize();
        $readings[] = array();
        foreach($result as $reading) {
            $readings[] = new NodeReading($reading->Current, $reading->Temp, new DateTime($reading->Timestamp), $reading->NodeID);
        }
        return $readings;
    }

    static private function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }
} 