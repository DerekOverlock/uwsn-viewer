<?php
class Node {
    private static $tbl_name = "Node";
    private static $primary_key = "NodeID";

    private $model;
    private $NodeID, $Name, $Latitude, $Longitude, $SerialNumber, $OwnedBy, $Description, $NetworkID;

    private function __construct($name, $latitude, $longitude, $serial_number, $owned_by, $node_id = NULL) {

    }

    static public function AddNode($name, $latitude, $longitude, $serial_number, $owned_by, $description, $network_id) {
        // add node to DB
        // grab new node id
        return self::getNode($new_node_id);
    }

    public function name($name = null) {
        if($name) {
            $this->Name = $name;
            return $this;
        } else {
            return $this->Name;
        }
    }

    public function latitude($latitude = null) {
        if($latitude) {
            $this->Latitude = $latitude;
            return $this;
        } else {
            return $this->Latitude;
        }
    }

    public function longitude($longitude = null) {
        if($longitude) {
            $this->Longitude = $longitude;
            return $this;
        } else {
            return $this->Longitude;
        }
    }

    public function serial_number($serial_number = null) {
        if($serial_number) {
            $this->SerialNumber = $serial_number;
            return $this;
        } else {
            return $this->SerialNumber;
        }
    }

    public function owned_by($owned_by = null) {
        if($owned_by) {
            $this->OwnedBy = $owned_by;
            return $this;
        } else {
            return $this->OwnedBy;
        }
    }

    /**
     * @return array(NodeReading)
     */
    public function getReadings() {
        if(!$this->NodeID) return false;
        return NodeReading::getNodeReadings($this->NodeID);
    }

    /**
     * @return array(NodeImage)
     */
    public function getImages() {
        if(!$this->NodeID) return false;
        return NodeImage::getImages($this->NodeID);
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
        $result = $db->query($sql)->itemize(__CLASS__);
        if($result) {
            $node = $result[0];
            return $node;
        } else {
            return false;
        }
    }

    static private function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


} 