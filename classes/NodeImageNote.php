<?php

class NodeImageNote {

    private static $tbl_name = "NodeImageNote";
    private static $primary_key = "NodeImageNoteID";

    private $node_image_id;
    private $timestamp;
    private $notes;
    private $uid;

    private $model;

    private $node_image_note_id;

    public function __construct($notes, $uid, $timestamp, $node_image_id, $node_image_note_id = NULL) {
        $this->model = self::getDatabase();

        $this->notes = $notes;
        $this->timestamp = $timestamp;
        $this->uid = $uid;

        $this->node_image_note_id = $node_image_note_id;
    }

    public function node_image_id() {
        return $this->node_image_id;
    }

    public function uid($uid = NULL) {
        if($uid) {
            $this->uid = $uid;
            return $this;
        } else {
            return $this->uid;
        }
    }

    public function notes($notes = NULL) {
        if($notes) {
            $this->notes = $notes;
            return $this;
        } else {
            return $this->notes;
        }
    }

    /**
     * @param DateTime $timestamp
     * @return DateTime
     */
    public function timestamp(DateTime $timestamp = null) {
        if($timestamp) {
            $this->timestamp = $timestamp;
            return $this;
        } else {
            return $this->timestamp;
        }
    }

    public function save() {
        $fields = array(
            "NodeImageID" => $this->node_image_id(),
            "Notes" => $this->notes(),
            "UID" => $this->uid(),
            "Timestamp" => $this->timestamp()->format("YYYY-mm-dd H:i:s")
        );

        $result = $this->model->save($fields, $this->node_image_note_id);
        if($result->success && !$this->node_image_note_id) {
            $this->node_image_note_id = $result->insert_id;
        }
        return $result;
    }

    /**
     * @param int $node_image_id
     * @return array(NodeImageNote)
     */
    public static function getImageNotes($node_image_id) {
        $db = self::getDatabase();
        $node_image_id = $db->sanitize($node_image_id);
        $sql = "SELECT * FROM ".self::$tbl_name." WHERE NodeImageID='{$node_image_id}'";
        $items = $db->query($sql)->itemize();
        $notes = array();
        foreach($items as $note) {
            $notes[] = new NodeImageNote($note->Notes, $note->UID, new DateTime($note->Timestamp), $note->NodeImageID, $note->NodeImageNoteID);
        }
        return $notes;
    }


    private static function getDatabase() {
        return new Database(self::$tbl_name, self::$primary_key);
    }


} 