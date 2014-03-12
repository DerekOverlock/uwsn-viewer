<?php

class NodeImageNote {

    private static $tbl_name = "NodeImageNote";
    private static $primary_key = "NodeImageNoteID";

    private $NodeImageID;
    /** @var  DateTime */
    private $Timestamp;
    private $Notes;
    private $UID;

    private $model;

    private $NodeImageNoteID;

    private function __construct() {
        $this->model = self::getDatabase();
    }

    /**
     * @param $notes
     * @param $uid
     * @param DateTime $timestamp
     * @param $node_image_id
     */
    static public function AddImageNote($notes, $uid, DateTime $timestamp, $node_image_id) {
        $db = self::getDatabase();
        $fields = array(
            "Notes" => $notes,
            "UID" => $uid,
            "Timestamp" => $timestamp->format('Y-m-d H:i:s'),
            "NodeImageID" => $node_image_id
        );
        $result = $db->save($fields);
        return self::getImageNote($result->insert_id);
    }

    public function node_image_id() {
        return $this->NodeImageID;
    }

    public function uid($uid = NULL) {
        if($uid) {
            $this->UID = $uid;
            return $this;
        } else {
            return $this->UID;
        }
    }

    public function notes($notes = NULL) {
        if($notes) {
            $this->Notes = $notes;
            return $this;
        } else {
            return $this->Notes;
        }
    }

    /**
     * @param DateTime $timestamp
     * @return DateTime
     */
    public function timestamp(DateTime $timestamp = null) {
        if($timestamp) {
            $this->Timestamp = $timestamp;
            return $this;
        } else {
            return $this->Timestamp;
        }
    }

    public function save() {
        $fields = array(
            "NodeImageID" => $this->NodeImageID,
            "Notes" => $this->Notes,
            "UID" => $this->UID,
            "Timestamp" => $this->Timestamp->format("Y-m-d H:i:s")
        );

        $result = $this->model->save($fields, $this->NodeImageNoteID);
        return $result;
    }

    /**
     * @param int $node_image_id
     * @return NodeImageNote[]
     */
    public static function getImageNotes($node_image_id) {
        $db = self::getDatabase();
        $node_image_id = $db->sanitize($node_image_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeImageID='{$node_image_id}'";
        return $db->query($sql)->itemize(__CLASS__);
    }

    /**
     * @param int $node_image_note_id
     * @return NodeImageNote
     */
    public static function getImageNote($node_image_note_id) {
        $db = self::getDatabase();
        $node_image_note_id = $db->sanitize($node_image_note_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeImageNoteID='{$node_image_note_id}'";
        $result = $db->query($sql)->itemize(__CLASS__);
        if($result) {
            return $result[0];
        } else {
            return false;
        }
    }

    private static function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


} 