<?php
class Node {
    private static $tbl_name = "NodeNetwork";
    private static $primary_key = "NetworkID";

    private $model;
    private $name;
    private $latitude;
    private $longitude;
    private $serial_number;
    private $owned_by;

    private $node_id;

    public function __construct($name, $latitude, $longitude, $serial_number, $owned_by, $node_id = NULL) {
        $this->model = self::getDatabase();
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->serial_number = $serial_number;
        $this->owned_by = $owned_by;
        $this->node_id = $node_id;
    }

    public function name($name = null) {
        if($name) {
            $this->name = $name;
            return $this;
        } else {
            return $this->name;
        }
    }

    public function latitude($latitude = null) {
        if($latitude) {
            $this->latitude = $latitude;
            return $this;
        } else {
            return $this->latitude;
        }
    }

    public function longitude($longitude = null) {
        if($longitude) {
            $this->longitude = $longitude;
            return $this;
        } else {
            return $this->longitude;
        }
    }

    public function serial_number($serial_number = null) {
        if($serial_number) {
            $this->serial_number = $serial_number;
            return $this;
        } else {
            return $this->serial_number;
        }
    }

    public function owned_by($owned_by = null) {
        if($owned_by) {
            $this->owned_by = $owned_by;
            return $this;
        } else {
            return $this->owned_by;
        }
    }

    /**
     * @return array(NodeReading)
     */
    public function getReadings() {
        if(!$this->node_id) return false;
        return NodeReading::getNodeReadings($this->node_id);
    }

    /**
     * @return array(NodeImage)
     */
    public function getImages() {
        if(!$this->node_id) return false;
        return NodeImage::getImages($this->node_id);
    }

    public function addReading($current, $temp, $timestamp) {
        if(!$this->node_id) return false;
        $node_reading = new NodeReading($current, $temp, $timestamp, $this->node_id);
        $result = $node_reading->save();
        if($result->success) {
            $this->readings[] = $node_reading;
        }
        return $result;
    }

    public function save() {
        $fields = array(
            "Name" => $this->name(),
            "Latitude" => $this->latitude(),
            "Longitude" => $this->longitude(),
            "SerialNumber" => $this->serial_number(),
            "OwnedBy" => $this->owned_by()
        );
        $result = $this->model->save($fields, $this->node_id);
        if($result->success && !$this->node_id) {
            $this->node_id = $result->insert_id;
        }
        return $result;
    }

    static public function getNode($node_id) {
        $db = self::getDatabase();
        $node_id = $db->sanitize($node_id);
        $sql = "SELECT * FROM ". self::$tbl_name . " WHERE NodeID = '$node_id'";
        $result = $db->query($sql)->itemize();
        if($result) {
            $node = $result[0];
            return new Node($node->Name, $node->Latitude, $node->Longitude, $node->SerialNumber, $node->OwnedBy, $node->NodeID);
        } else {
            return false;
        }
    }

    static private function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


} 