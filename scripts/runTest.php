<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/RMacTest.php";

$networkId = $_GET['networkId'];
$targetNodeId = $_GET['targetNodeId'];
$email = $_GET['$email'];

$test = new RMacTest(new NodeTest($networkId, $targetNodeId));
$testResult = $test->runTest();

$parser = new TraceFileParser($testResult->traceFile);

$packets = $parser->parse();

