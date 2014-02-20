<?php
header('Content-type: text/plain');

require_once __DIR__ . "/config.inc.php";
require_once PHP_LIB . "/User.php";

$user = User::getUserWithEmail("shilumin@gmail.com");

$user->setEmail("overlock.derek@gmail.com");
$result = $user->save();
print_r($result);

echo $user->firstName()." ".$user->lastName()." ".$user->email()." ".$user->password()." ".$user->uid();