<?php
require_once __DIR__ . "/../config.inc.php";
require_once PHP_LIB . "/RMacTest.php";
require_once PHP_LIB . "/Mail.php";

$networkId = $_GET['networkId'];
$targetNodeId = $_GET['targetNodeId'];
$email = $_GET['$email'];

$test = new RMacTestSuite($networkId, $targetNodeId);

$testResults = $test->aggregateTestResults();

if($email) {
    $mail = new Mail();
    $mail->send($email, "Test Results", "<p>Here are your test results:</p><pre>".$testResults->toString()."</pre>", $test->getTestResults()->toArray());
}

echo "Simulation results:\n";
echo $testResults->toString();
echo "\n";
if($email) {
    echo "Sending email to $email";
}
