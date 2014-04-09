<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Node.php";

header('Content-type: application/json');

$networkId = $_POST['networkId'];
$lat = $_POST['lat'];
$long = $_POST['long'];
$alt = $_POST['alt'];

$node = Node::AddNode("", $lat, $long, $alt, $networkId);

$result = new stdClass;

$result->nodeId = $node->getId();
$result->success = true;

echo json_encode($result);