<?php

class NodeImage {

    private static $tbl_name = "NodeImage";
    private static $primary_key = "NodeImageID";

    private $NodeID, $Image, $Timestamp, $NodeImageID;
    /**
     * @var Database
     */
    private $model;


    private function __construct() {
        $this->model = self::getDatabase();
    }


    static public function AddImage($image, DateTime $timestamp, $node_id) {
        $db = self::getDatabase();
        $fields = array(
            "Image" => $image,
            "Timestamp" => $timestamp->format('Y-m-d H:i:s'),
            "NodeID" => $node_id
        );

        $result = $db->save($fields);
        return self::getImage($result->insert_id);
    }

    public function image($image = NULL) {
        if($image) {
            $this->Image = $image;
            return $this;
        } else {
            return $this->Image;
        }
    }

    public function node_id() {
        return $this->NodeID;
    }

    public function save() {
        $fields = array(
            "NodeID" => $this->NodeID,
            "Image" => $this->Image
        );

        return $this->model->save($fields, $this->NodeImageID);
    }

    /**
     * @return NodeImageNote[]
     */
    public function getImageNotes() {
        return NodeImageNote::getImageNotes($this->NodeImageID);
    }

    /**
     * @param int $node_id
     * @return NodeImage[]
     */
    public static function getImages($node_id) {
        $db = self::getDatabase();
        $node_id = $db->sanitize($node_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeID='{$node_id}'";
        return $db->query($sql)->itemize(__CLASS__);
    }

    /**
     * @param int $node_img_id
     * @return NodeImage
     */
    public static function getImage($node_img_id) {
        $db = self::getDatabase();
        $node_img_id = $db->sanitize($node_img_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeImageID='{$node_img_id}'";
        $result = $db->query($sql)->itemize(__CLASS__);
        if($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * @return Database
     */
    private static function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


} 