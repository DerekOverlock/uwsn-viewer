<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Database.php";

class NodeNetwork {
    private static $tbl_name = "NodeNetwork";
    private static $primary_key = "NetworkId";

    private $model;
    private $Name, $NetworkId;

    private function __construct() {
        $this->model = self::getDatabase();
    }

    /**
     * @param $name
     * @return NodeNetwork
     */
    static public function AddNetwork($name) {
        $db = self::getDatabase();
        $fields = array(
            "Name" => $name
        );
        $result = $db->save($fields);
        return self::getNetwork($result->insert_id);
    }

    public function getName() {
        return $this->Name;
    }

    public function getId() {
        return $this->NetworkId;
    }

    public function save() {
        $fields = array(
            "Name" => $this->Name
        );
        $result = $this->model->save($fields, $this->NetworkId);

        return $result;
    }

    public function getNodesInNetwork() {
        return Node::getNodesInNetwork($this->NetworkId);
    }

    /**
     * @return NodeNetwork[]
     */
    static public function getNetworks() {
        $db = self::getDatabase();
        $sql = "SELECT * FROM ". self::$tbl_name . " ORDER BY Name";
        return $db->query($sql)->itemize(__CLASS__);
    }

    /**
     * @param $network_id
     * @return bool
     */
    static public function getNetwork($network_id) {
        $db = self::getDatabase();
        $network_id = $db->sanitize($network_id);
        $sql = "SELECT * FROM ". self::$tbl_name . " WHERE NetworkId = '$network_id' ORDER BY Name";
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

/*
$network = NodeNetwork::AddNetwork("Test");
$network->getId();
*/