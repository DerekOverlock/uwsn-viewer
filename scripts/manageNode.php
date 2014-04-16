<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Node.php";

header('Content-type: application/json');

$networkId = $_POST['networkId'];
$name = (isset($_POST['name'])) ? $_POST['name'] : null;
$id = (isset($_POST['id'])) ? $_POST['id'] : null;
$lat = $_POST['lat'];
$long = $_POST['long'];
$alt = $_POST['alt'];

if($id && ($node = Node::getNode($id))) {
    $node->Name = $name;
    $node->Latitude = $lat;
    $node->Longitude = $long;
    $node->Altitude = $alt;
    $node->save();
} else {
    $node = Node::AddNode("", $lat, $long, $alt, $networkId);
}



$result = new stdClass;

$result->nodeId = $node->getId();
$result->success = true;

echo json_encode($result);