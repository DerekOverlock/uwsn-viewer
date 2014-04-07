<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/NodeNetwork.php";

$name = $_POST['name'];

$network = NodeNetwork::AddNetwork($name);

echo $network->getId();

/**
$node = Node::getNode($node->getId());
$node->updateLocation($lat, $long);
 * */