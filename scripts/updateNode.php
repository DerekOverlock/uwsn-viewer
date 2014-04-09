<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Node.php";

header('Content-type: application/json');

$name = $_POST['name'];
$alt = $_POST['elevation'];
$nodeId = $_POST['nodeId'];

$node = Node::getNode($nodeId);

$node->Name = $name;
$node->Altitude = $alt;

echo json_encode($node->save());