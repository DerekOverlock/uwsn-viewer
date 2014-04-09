<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Node.php";

header('Content-type: application/json');

$id = (isset($_GET['nodeId'])) ? $_GET['nodeId'] : null;

$result = new stdClass;
$result->success = false;

if($id && ($node = Node::getNode($id))) {
    $node->delete();
    $result->success = true;
}

echo json_encode($result);