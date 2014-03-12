<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Database.php";

class NodeReading {
    private static $tbl_name = "NodeReading";
    private static $primary_key = "NodeReadID";

    private $model;
    private $Current;
    private $Temp;
    /**
     * @var DateTime
     */
    private $Timestamp;
    private $NodeID;
    private $NodeReadID;

    private function __construct() {
        $this->model = self::getDatabase();
    }

    /**
     * @param $current
     * @param $temp
     * @param DateTime $timestamp
     * @param $node_id
     * @return NodeReading
     */
    static public function AddNodeReading($current, $temp, $timestamp, $node_id) {
        $db = self::getDatabase();
        $fields = array(
            "Current" => $current,
            "Temp" => $temp,
            "Timestamp" => $timestamp->format('Y-m-d H:i:s'),
            "NodeID" => $node_id
        );
        $result = $db->save($fields);
        return self::getNodeReading($result->insert_id);
    }

    public function save() {
        $fields = array(
            "Current" => $this->Current,
            "Temp" => $this->Temp,
            "Timestamp" => $this->Timestamp->format("Y-m-d H:i:s"),
            "NodeID" => $this->NodeID
        );
        $result = $this->model->save($fields, $this->NodeReadID);
        return $result;
    }

    public function current($current = NULL) {
        if($current) {
            $this->Current = $current;
            return $this;
        } else {
            return $this->Current;
        }
    }

    public function temp($temp = NULL) {
        if($temp) {
            $this->Temp = $temp;
            return $this;
        } else {
            return $this->Temp;
        }
    }

    /**
     * @param DateTime $timestamp
     * @return DateTime
     */
    public function timestamp(DateTime $timestamp = NULL) {
        if($timestamp) {
            $this->Timestamp = $timestamp;
            return $this;
        } else {
            return $this->Timestamp;
        }
    }

    public function node_id($node_id = NULL) {
        if($node_id) {
            $this->NodeID = $node_id;
            return $this;
        } else {
            return $this->NodeID;
        }
    }

    /**
     * @param $node_id
     * @return NodeReading[]
     */
    static public function getNodeReadings($node_id) {
        $db = self::getDatabase();
        $node_id = $db->sanitize($node_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeID = '".$node_id."'";
        return $db->query($sql)->itemize(__CLASS__);
    }

    /**
     * @param $node_id
     * @return NodeReading
     */
    static public function getNodeReading($nr_id) {
        $db = self::getDatabase();
        $nr_id = $db->sanitize($nr_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeReadID = '".$nr_id."'";
        $result = $db->query($sql)->itemize(__CLASS__);
        if($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    static private function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }
} 