<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Node.php";

$networkId = $_POST['networkId'];
$name = $_POST['name'];
$lat = $_POST['lat'];
$long = $_POST['long'];
$alt = $_POST['alt'];

$node = Node::AddNode($name, $lat, $long, $alt, $networkId);

/**
$node = Node::getNode($node->getId());
$node->updateLocation($lat, $long);
 * */