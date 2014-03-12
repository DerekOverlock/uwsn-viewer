<?php
class NodeNetwork {
    private static $tbl_name = "NodeNetwork";
    private static $primary_key = "NetworkID";

    private $model;
    private $Name, $Description, $NetworkID;

    private function __construct() {
        $this->model = self::getDatabase();
    }

    static public function AddNetwork($name, $description) {
        $db = self::getDatabase();
        $fields = array(
            "Name" => $name,
            "Description" => $description
        );
        $result = $db->save($fields);
        return self::getNetwork($result->insert_id);
    }

    public function name($name = null) {
        if($name) {
            $this->Name = $name;
            return $this;
        } else {
            return $this->Name;
        }
    }

    public function description($description = null) {
        if($description) {
            $this->Description = $description;
            return $this;
        } else {
            return $this->Description;
        }
    }

    public function save() {
        $fields = array(
            "Name" => $this->Name,
            "Description" => $this->Description
        );
        $result = $this->model->save($fields, $this->NetworkID);

        return $result;
    }

    public function getNodesInNetwork() {
        return Node::getNodesInNetwork($this->NetworkID);
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
        $sql = "SELECT * FROM ". self::$tbl_name . " WHERE NetworkID = '$network_id' ORDER BY Name";
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