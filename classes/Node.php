<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/GPSCoordinates.php";
require_once PHP_LIB . "/NodeNetwork.php";
require_once PHP_LIB . "/Database.php";

class Node {
    private static $tbl_name = "Node";
    private static $primary_key = "NodeID";

    private $model;
    private $NodeId, $NetworkId, $Name, $Latitude, $Longitude, $Altitude;

    /** @var  GPSCoordinates */
    private $gps;

    private function __construct() {
        $this->model = self::getDatabase();
        $this->gps = new GPSCoordinates($this->Latitude, $this->Longitude, $this->Altitude);
    }

    static public function AddNode($name, $latitude, $longitude, $altitude, $network_id) {
        $model = self::getDatabase();
        $fields = array(
            "Name" => $name,
            "Latitude" => $latitude,
            "Longitude" => $longitude,
            "Altitude" => $altitude,
            "NetworkId" => $network_id
        );

        $result = $model->save($fields);
        return self::getNode($result->insert_id);
    }

    public function getName() {
        return $this->Name;
    }

    public function getLatitude() {
        return $this->Latitude;
    }

    public function getLongitude() {
        return $this->Longitude;
    }

    public function getCoordinates() {
        return $this->gps;
    }


    public function getNetworkId() {
        return $this->NetworkId;
    }

    public function getId() {
        return $this->NodeId;
    }

    public function save() {
        $fields = array(
            "Name" => $this->Name,
            "Latitude" => $this->Latitude,
            "Longitude" => $this->Longitude,
            "Altitude" => $this->Altitude,
            "NetworkId" => $this->NetworkId
        );
        $result = $this->model->save($fields, $this->NodeId);
        return $result;
    }

    /**
     * @param $node_id
     * @return Node
     */
    static public function getNode($node_id) {
        $db = self::getDatabase();
        $node_id = $db->sanitize($node_id);
        $sql = "SELECT * FROM ". self::$tbl_name . " WHERE NodeId = '$node_id'";
        $result = $db->query($sql)->itemize(__CLASS__);
        if($result) {
            $node = $result[0];
            return $node;
        } else {
            return false;
        }
    }

    /**
     * @param $network_id
     * @return Node[]
     */
    static public function getNodesInNetwork($network_id) {
        $db = self::getDatabase();
        $network_id = $db->sanitize($network_id);
        $sql = "SELECT * FROM ". self::$tbl_name . " WHERE NetworkId = '$network_id'";
        $result = $db->query($sql)->itemize(__CLASS__);
        return $result;
    }

    static private function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


}