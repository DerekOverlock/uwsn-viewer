var app = app || {};

app.NodeModel = Backbone.Model.extend({
    initialize: function() {
        this.id = this.get('NodeId');
    },
    urlRoot: 'scripts/getModel.php',
    url: function() {
        return this.urlRoot + '?id=' + this.id;
    },
    updateLocation: function(lat, long, alt) {
        console.log(alt);
        this.set('Latitude', lat);
        this.set('Longitude', long);
        this.set('Altitude', alt);
    },
    sync: function() {
        var ctx = this;
        var params = {
            lat: this.get('Latitude'),
            long: this.get('Longitude'),
            alt: this.get('Altitude'),
            networkId: this.get('NetworkId'),
            name: this.get('Name'),
            id: this.get('NodeId')
        };
        $.post('scripts/manageNode.php',
            params,
            function(newNode) {
                console.log(newNode);
                ctx.set('NodeId', newNode.nodeId);
                ctx.id = newNode.nodeId;
                ctx.trigger('sync');
            }
        );
    },
    remove: function() {
        $.get(
            'scripts/deleteNode.php',
            {nodeId: this.id},
            function(response) {
            }
        );
    }
});

app.NodeListCollection = Backbone.Collection.extend({
    initialize: function(models, options) {
        this.networkId = options.networkId;
    },
    model: app.NodeModel,
    fetch: function() {
        var ctx = this;
        $.get(this.url(), function(nodes) {
            ctx.reset(nodes);
        });
    },
    urlRoot: 'scripts/getNodesInNetwork.php',
    url: function() {
        return this.urlRoot + '?networkId=' + this.networkId;
    }
});

app.SimulationView = Backbone.View.extend({
    el: 'body',
    initialize: function(options) {
        this.networkId = options.networkId;
        this.mapOptions = {
            center: new google.maps.LatLng(30.5522, -87.9913),
            zoom: 10
        };
        this.elevation = new google.maps.ElevationService();
        this.infoWindow = new google.maps.InfoWindow();
        this.nodes = new app.NodeListCollection(null, {networkId: this.networkId});
        this.listenTo(this.nodes, 'reset', this.renderNodeList);
        this.listenTo(this.nodes, 'remove', this.renderNodeList);
        this.listenToOnce(this.nodes, 'reset', this.registerCurrentNodes);
        this.listenTo(this.nodes, 'add', this.addMarker);
        this.nodes.fetch();
    },
    render: function() {
        var ctx = this;
        this.map = new google.maps.Map($("#map-canvas").get(0), this.mapOptions);
        google.maps.event.addListener(this.map, 'click', function(mapObj) { ctx.addNode.call(ctx, mapObj); });

    },
    events: {
        'change #nodeSelect' : 'updateSelectedNode',
        'click #update_node_btn' : 'updateNodeDetails',
        'click #delete_node_btn' : 'deleteNode',
        'click #start_sim_btn' : 'runTest'
    },
    addMarker: function(nodeModel, collection, state) {
        var ctx = this;
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(nodeModel.get('Latitude'), nodeModel.get('Longitude')),
            draggable:true,
            map: this.map,
            nodeModel: nodeModel
        });
        google.maps.event.addListener(marker, 'click', function(mapObj) { ctx.showInfoWindow.call(ctx, marker); })
        google.maps.event.addListener(marker,
            'dragstart',
            function() {
               ctx.infoWindow.close();
            }
        );
        google.maps.event.addListener(
            marker,
            'dragend',
            function(mapObj) {
                var nodeModel = marker.nodeModel;
                var pos = mapObj.latLng;
                var lat = pos.lat();
                var lng = pos.lng();
                ctx.getElevation(pos, function(results, status) {
                    var elevation = results[0].elevation;
                    nodeModel.updateLocation(lat, lng, elevation);
                    nodeModel.sync();
                });
            }
        );
        nodeModel.set('marker', marker);
    },
    showInfoWindow: function(marker) {
        var latlng = new google.maps.LatLng( marker.position.lat() + 0.04, marker.position.lng() );
        var name = (marker.nodeModel.get('Name')) ? marker.nodeModel.get('Name') : '';
        var elevation = marker.nodeModel.get('Altitude');
        var nodeId = marker.nodeModel.get('NodeId');
        this.infoWindow.setContent(
            '<form role="form" style="padding: 15px" class="col-md-10" id="nodeInfoForm">' +
                '<div class="form-group">'+
                '<label for="nodeName">Node Name</label>' +
                '<div class="col-sm-10"><input type="text" class="form-control" name="node_name" value="'+name+'"/></div>' +
                '</div>'+
                '<div class="form-group">' +
                '<label for="nodeElevation">Node Elevation</label>' +
                '<div class="col-sm-10"><input type="text" class="form-control" name="elevation" value="'+new Number(elevation).toPrecision(6)+'" /></div>' +
                '</div>'+
                '<div><h5>' +
                'Coordinates: ('+marker.position.lat().toPrecision(6)+', ' +marker.position.lng().toPrecision(6)+ ')' +
                '</h5></div>'+
                '<div class="form-group">' +
                '<input type="hidden" class="form-control" name="nodeId" value="'+nodeId+'" />' +
                '<button type="button" class="btn btn-primary" id="update_node_btn">Save Node</button>'+
                '<button type="button" class="btn btn-danger" id="delete_node_btn">Delete Node</button>' +
                '</div>'+
                '</form>'
        );
        this.infoWindow.setPosition(latlng);
        this.infoWindow.open(this.map);
    },
    getElevation: function(location, callback) {
        this.elevation.getElevationForLocations({locations: [location]}, callback);
    },
    addNode: function(event) {
        var ctx = this;
        var location = event.latLng;
        var elevationCallback = function(results, status) {
            if(status != google.maps.ElevationStatus.OK) { alert('Elevation service failed.'); return; }
            if(!results[0]) { alert('No results.'); return; }
            var elevation = results[0].elevation;
            var nodeModel = new app.NodeModel();
            ctx.listenToOnce(nodeModel, 'sync', function() {
                ctx.nodes.add(nodeModel);
                ctx.showInfoWindow(nodeModel.get('marker'));
            }, ctx);
            ctx.listenTo(nodeModel, 'sync', ctx.renderNodeList, ctx);
            nodeModel.set('Latitude', location.lat());
            nodeModel.set('Longitude', location.lng());
            nodeModel.set('Altitude', elevation);
            nodeModel.set('NetworkId', ctx.networkId);
            nodeModel.sync();
        };
        this.getElevation(location, elevationCallback);
    },
    registerCurrentNodes: function() {
        var ctx = this;
        this.nodes.each(function(node) {
            ctx.addMarker(node);
            ctx.listenTo(node, 'sync', ctx.renderNodeList, ctx);
        });
    },
    renderNodeList: function() {
        var ctx = this;
        var select = $("#nodeSelect");
        select.empty();
        this.nodes.each(function(node) {
           var label = (node.get('Name')) ? node.get('Name') : 'Node ' + node.id;
           var option = $("<option value='"+node.id+"'>"+label+"</option>").appendTo(select);
           option.data('model', node);
        });
    },
    updateSelectedNode: function(event) {
        var tgt = $(event.target);
        this.selectedNode = tgt.find(':selected').data('model');
        this.showInfoWindow(this.selectedNode.get('marker'));
    },
    updateNodeDetails: function(event) {
        var tgt = $(event.target);
        var form = tgt.closest("form");
        var name = form.find('input[name="node_name"]').val();
        var elevation = form.find('input[name="elevation"]').val();
        var id = form.find('input[name="nodeId"]').val();
        var node = this.nodes.get(id);
        node.set('Name', name);
        node.set('Altitude', elevation);
        node.sync();
        this.infoWindow.close();
    },
    deleteNode: function(event) {
        var tgt = $(event.target);
        var form = tgt.closest("form");
        var id = form.find('input[name="nodeId"]').val();
        var node = this.nodes.get(id);
        var marker = node.get('marker');
        marker.setMap(null);
        this.nodes.remove(node);
        node.remove();
        this.infoWindow.close();
    },
    runTest: function() {
        if(!this.selectedNode) { alert('Please select a target node in the list.'); return;}
        var params = {
            networkId: this.networkId,
            targetNodeId: this.selectedNode.id,
            email: $("#emailaddr").val()
        };
        console.log(params);
        $.get(
            'scripts/runTest.php',
            params,
            function(response) {
                $("#simulation-result-box").html(response);
            }
        );
    }

});