<?php
//require_once __DIR__ . "/../config.inc.php";
//require_once PHP_LIB . "/NodeNetwork.php";

$name = $_POST['NetworkName'];

//$network = NodeNetwork::AddNetwork($name);

$return = new stdClass();
//$return->networkID = $network->getId();
$return->networkID = 1;
$return->networkName = $name;

header("Content-Type: json/application");

echo json_encode($return);

/**
$node = Node::getNode($node->getId());
$node->updateLocation($lat, $long);
 * */