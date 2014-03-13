<?php

$obj = new stdClass();
$obj->Name = "Test Network";
$obj->Description = "This is a test network to demonstrate Backbone.js";
$obj->NetworkID = 1;

echo json_encode($obj);