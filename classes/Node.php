<?php
class Node {
    private static $tbl_name = "Node";
    private static $primary_key = "NodeID";

    private $model;
    private $NodeID, $Name, $Latitude, $Longitude, $SerialNumber, $OwnedBy, $Description, $NetworkID;

    private function __construct() {
        $this->model = self::getDatabase();
    }

    static public function AddNode($name, $latitude, $longitude, $serial_number, $owned_by, $description, $network_id) {
        $model = self::getDatabase();
        $fields = array(
            "Name" => $name,
            "Latitude" => $latitude,
            "Longitude" => $longitude,
            "SerialNumber" => $serial_number,
            "OwnedBy" => $owned_by,
            "Description" => $description,
            "NetworkID" => $network_id
        );

        $result = $model->save($fields);

        return self::getNode($result->insert_id);
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

    public function description($decription = null) {
        if($decription) {
            $this->Description = $decription;
            return $this;
        } else {
            return $this->Description;
        }
    }

    public function network_id($network_id = null) {
        if($network_id) {
            $this->NetworkID = $network_id;
            return $this;
        } else {
            return $this->NetworkID;
        }
    }

    /**
     * @return NodeReading[]
     */
    public function getReadings() {
        return NodeReading::getNodeReadings($this->NodeID);
    }

    /**
     * @return NodeImage[]
     */
    public function getImages() {
        return NodeImage::getImages($this->NodeID);
    }

    public function addReading($current, $temp, $timestamp) {
        return NodeReading::AddNodeReading($current, $temp, $timestamp, $this->NodeID);
    }

    public function save() {
        $fields = array(
            "Name" => $this->name(),
            "Latitude" => $this->latitude(),
            "Longitude" => $this->longitude(),
            "SerialNumber" => $this->serial_number(),
            "OwnedBy" => $this->owned_by()
        );
        $result = $this->model->save($fields, $this->NodeID);
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

    static public function getNodesInNetwork($network_id) {
        $db = self::getDatabase();
        $network_id = $db->sanitize($network_id);
        $sql = "SELECT * FROM ". self::$tbl_name . " WHERE NetworkID = '$network_id'";
        $result = $db->query($sql)->itemize(__CLASS__);
        return $result;
    }

    static private function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


} 