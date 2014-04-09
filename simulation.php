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
    <script src="js/views/SimulationView.js"></script>
    <script type="text/javascript">
        $(function() {
            app.currentView = new app.SimulationView({networkId: '<?=$_GET['networkID']?>'});
            app.currentView.render();
        });
    </script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
        html { height: 100% }
        body { height: 100%;}
        #map-canvas { width: 100%; height: 100%; }
    </style>

</head>

<body>
    <div class="container" >
        <h2>UWSN Simulator</h2>
        <input type="hidden" value="<?=$_GET['networkID']?>" id="networkID">
        <div class="col-md-8" style="height: 600px;">
            <div id="map-canvas"></div>
        </div>
        <div class="col-md-4">
            <select multiple class="form-control" id="nodeSelect" style="height: 600px;">
            </select>
        </div>
    </div>
    <div id="sim-box" class="container" style="margin-top: 20px">
        <form class="form-inline" role="form">
            <div class="form-group">
                <label for="emailaddr">Email</label>
                <input type="text" name="email" class="form-control" id="emailaddr" placeholder="optional, for test results">
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="start_sim_btn">Start</button>
        </form>
    </div>
    <div class="container">
        <h3>Result Console:</h3>
        <pre class="code" id="simulation-result-box">

        </pre>
    </div>
</body>

</html>