<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/Node.php";

$networkId = $_GET['networkId'];

echo json_encode(Node::getNodesInNetwork($networkId));