var app = {};

app.SERVER = '/uwsn-viewer';

$(function(){
    google.maps.event.addDomListener(window, 'load', app.Init);
});

app.Init = function() {
    var mapCenter = new google.maps.LatLng(30.5522, -87.9913); // mobile bay!!!
    var mapInitOptions = {
        center: mapCenter,
        zoom: 10
    };

    // set map variable
    app.map = new google.maps.Map(document.getElementById("map-canvas"), mapInitOptions);
    // set elevation service
    app.elevator = new google.maps.ElevationService();
    // set info window
    app.infowindow = new google.maps.InfoWindow();
    // set nodes array
    app.nodes = [];

    // listen to map clicks, default is add node mode
    app.LController.addNodeMode();

    // really it is an event delegation
    app.LController.add_update_node_btn_listener();
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
        //if(!event.map)
            app.NodeLib.makeNewNode(event.latLng);
    });
};

app.LController.addInfoWindowListener = function(node) {
    google.maps.event.addListener(
        node,
        'click',
        function(){
            app.NodeLib.makeInfoWindowForNode(node);
        }
    );
};

app.LController.add_update_node_btn_listener = function() {
    $("#map-canvas").on('click', "#update_node_btn", function(){
        var form = $(this).closest("form").get(0);
        console.log(form);
        $.post(app.SERVER+'/scripts/updateNode.php', {name:form.node_name.value, elevation:form.elevation.value, nodeId: form.nodeId.value}, function(result){
            console.log(result);
        });
    })
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
    };

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
app.NodeLib.makeInfoWindowForNode = function(node) {
    var latlng = new google.maps.LatLng( node.position.lat(), node.position.lng() );
    app.infowindow.setContent(
        '<form role="form" style="padding: 15px" class="col-md-10" id="nodeInfoForm">' +
            '<div class="form-group">'+
                '<label for="nodeName">Node Name</label>' +
                '<div class="col-sm-10"><input type="text" class="form-control" name="node_name" /></div>' +
            '</div>'+
            '<div class="form-group">' +
                '<label for="nodeElevation">Node Elevation</label>' +
                '<div class="col-sm-10"><input type="text" class="form-control" name="elevation" value="'+node.nodeElevation.toPrecision(6)+'" /></div>' +
            '</div>'+
            '<div><h5>' +
                'Coordinates: ('+node.position.lat().toPrecision(6)+', ' +node.position.lng().toPrecision(6)+ ')' +
            '</h5></div>'+
            '<div class="form-group">' +
                '<input type="hidden" class="form-control" name="nodeId" value="'+node.nodeID+'" />' +
                '<button type="button" class="btn btn-primary" id="update_node_btn">update</button>'+
            '</div>'+
        '</form>'
    );
    app.infowindow.setPosition(latlng);
    app.infowindow.open(app.map);
};
app.NodeLib.makeNewNode = function(location) {
    var locations = [];
    var lat = location.lat(); var lng = location.lng();
    var latlng = new google.maps.LatLng(lat, lng);

    locations.push(latlng);

    var positionalRequest = {
        'locations': locations
    };

    app.elevator.getElevationForLocations(positionalRequest, function(results, status) {
        if (status == google.maps.ElevationStatus.OK) {
            // Retrieve the first result
            if (results[0]) {
                var elevation = results[0].elevation;
                var networkID = $("#networkID").val();
                $.post(app.SERVER+'/scripts/addNodeToNetwork.php', {lat:lat, long: lng, alt: elevation, networkId: networkID}, function(result){
                    var nodeID = result.nodeId;
                    var node = new google.maps.Marker({
                        position: location,
                        draggable:true,
                        map: app.map,
                        nodeID: nodeID,
                        nodeElevation: elevation
                    });
                    app.nodes.push(node);
                    var size = app.nodes.length;
                    app.LController.addInfoWindowListener(app.nodes[size-1]);
                    app.NodeLib.makeInfoWindowForNode(app.nodes[size-1]);
                });
            } else {
                alert('No results found');
            }
        } else {
            alert('Elevation service failed due to: ' + status);
        }
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

