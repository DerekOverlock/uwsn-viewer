<?php

class NodeImage {

    private static $tbl_name = "NodeImage";
    private static $primary_key = "NodeImageID";

    private $node_id;
    private $image;

    private $model;

    private $node_image_id;

    public function __construct($image, $node_id, $node_image_id = NULL) {
        $this->model = self::getDatabase();

        $this->image = $image;
        $this->node_id = $node_id;

        $this->node_image_id = $node_image_id;
    }

    public function image($image = NULL) {
        if($image) {
            $this->image = $image;
            return $this;
        } else {
            return $this->image;
        }
    }

    public function node_id() {
        return $this->node_id;
    }

    public function save() {
        $fields = array(
            "NodeID" => $this->node_id(),
            "Image" => $this->image()
        );

        $result = $this->model->save($fields, $this->node_image_id);
        if($result->success && !$this->node_image_id) {
            $this->node_image_id = $result->insert_id;
        }
        return $result;
    }

    /**
     * @return array(NodeImageNote)
     */
    public function getImageNotes() {
        if(!$this->node_image_id) return false;
        return NodeImageNote::getImageNotes($this->node_image_id);
    }

    /**
     * @param int $node_id
     * @return array(NodeImage)
     */
    public static function getImages($node_id) {
        $db = self::getDatabase();
        $node_id = $db->sanitize($node_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeID='{$node_id}'";
        $items = $db->query($sql)->itemize();
        $images = array();
        foreach($items as $image) {
            $images[] = new NodeImage($image->Image, $image->NodeID, $image->NodeImageID);
        }
        return $images;
    }


    private static function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


} 