var app = {};

app.SERVER = '/uwsn-viewer';

$(function(){
    google.maps.event.addDomListener(window, 'load', app.Init);
});

app.Init = function() {
    var mapCenter = new google.maps.LatLng(39.153160, 125.035400); // north korea!!
    var mapInitOptions = {
        center: mapCenter,
        zoom: 8
    };

    // set map variable
    app.map = new google.maps.Map(document.getElementById("map-canvas"), mapInitOptions);
    // set elevation service
    app.elevator = new google.maps.ElevationService();
    // set info window
    app.infowindow = new google.maps.InfoWindow();
    // set nodes array, actually it's an object tied with nodeIDs
    app.nodes = {};

    // listen to map clicks, default is add node mode
    app.LController.addNodeMode();
    //app.mapListener = google.maps.event.addListener(app.map, 'click', app.NodeLib.getElevation);
};

// Define Listener Controller functions
app.LController = {};

app.LController.removeMapListener = function() {
    if(app.mapListener) google.maps.event.removeListener(app.mapListener);
};

app.LController.addNodeMode = function() {
    app.LController.removeMapListener();
    // add correct listener
    app.mapListener = google.maps.event.addListener(app.map, 'click', function(event) {
        app.NodeLib.makeNewNode(event.latLng);
    });
};

app.LController.addElevationListener = function(node) {
    google.maps.event.addListener(node, 'click', app.NodeLib.getElevation);
};

// Define NodeLib functions
app.NodeLib = {};

app.NodeLib.getNodesInNetwork = function(networkID) {
    $.get(app.SERVER+"/scripts/getNodesInNetwork.php?networkId="+networkID, function(result){
        console.log(result);
    });
};

app.NodeLib.getElevation = function(event) {
    var locations = [];

    // Retrieve the clicked location and push it on the array
    var clickedLocation = event.latLng;
    locations.push(clickedLocation);

    // Create a LocationElevationRequest object using the array's one value
    var positionalRequest = {
        'locations': locations
    }

    // Initiate the location request
    app.elevator.getElevationForLocations(positionalRequest, function(results, status) {
        if (status == google.maps.ElevationStatus.OK) {

            // Retrieve the first result
            if (results[0]) {

                // Open an info window indicating the elevation at the clicked position
                app.infowindow.setContent('The elevation at this point <br>is ' + results[0].elevation + ' meters.');
                app.infowindow.setPosition(clickedLocation);
                app.infowindow.open(app.map);
            } else {
                alert('No results found');
            }
        } else {
            alert('Elevation service failed due to: ' + status);
        }
    });
};
app.NodeLib.getElevationByLatLng = function(lat, lng) {
    var locations = [];
};

app.NodeLib.makeNewNode = function(location) {
    $.post(app.SERVER, {lat:location.lat(), lng: location.lng()}, function(result){
        //var nodeID = result.nodeID;
        // todo
        var nodeID = 1;
        var node = new google.maps.Marker({
            position: location,
            draggable:true,
            map: app.map,
            nodeID: nodeID
        });
        app.nodes[nodeID] = (node);
        app.LController.addElevationListener(app.nodes[nodeID]);
    });
};

app.NodeLib.deleteNode = function(nodeID) {
    delete app.nodes[nodeID];
};

// show or hide nodes
app.NodeLib.setAllMap = function(map) {
    for (var i = 0; i < app.nodes.length; i++) {
        app.nodes[i].setMap(map);
    }
};
app.NodeLib.clearNodes = function() {
    app.NodeLib.setAllMap(null);
};


app.NodeLib.getNodeLat = function(node) {
    return node.getPosition().lat();
};
app.NodeLib.getNodeLng = function(node) {
    return node.getPostion.lng();
};

