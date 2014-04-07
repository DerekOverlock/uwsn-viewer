<?php
//header('Content-type: text/plain');
//require_once __DIR__ . "/config.inc.php";
//require_once PHP_LIB . "/User.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.2/backbone-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/mustache.js/0.7.2/mustache.min.js"></script>

    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDR4kXFcx8iRKOaQ2ZgukZnxIKC_KAyCfA&sensor=false">
    </script>
    <script src="js/Node.js"></script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        html { height: 100% }
        body { height: 100%;}
        #map-canvas { height: 80%; width: 80%; margin-left: 5%}
    </style>

</head>

<body>
<div id="sim-tools-box" class="container">
    <h3>UWSN Simulator</h3>
    <h4>Current Mode: Add Node Mode</h4>
    <button type="button" class="btn btn-info">Add Node Mode</button>
    <button type="button" class="btn btn-warning">Delete Node Mode</button>
    <button type="button" class="btn btn-primary">Start Simulation!</button>
</div>
<div id="map-canvas" class="container" style="float: left"></div>
<div style="float: left"><h5>Node List:</h5></div>
<div style="clear:both"></div>

</body>
</html>