$(function(){ app.Init(); });

var app = {};

app.Init = function() {
    app.EventListeners.add_network_btn();
    app.EventListeners.test_network_btn();
    app.EventListeners.delete_network_btn();
};

app.SERVER = '';
app.EventListeners = {};
app.Controller = {};

app.EventListeners.add_network_btn = function() {
    $("#add_network_btn").on('click', function(){
        var f = $(this).closest('form');
        var data = $(f).serialize();
        $.post("/scripts/addNetwork.php", data, function(result){
            var networkID = result.networkID;
            var networkName = result.networkName;
            var row = '<tr>' +
                        '<td>'+networkID+'</td> <td>'+networkName+'</td>' +
                        '<td><button type="button" class="test_network_btn btn btn-sm btn-info">Test</button></td>' +
                        '<td><button type="button" class="delete_network_btn btn btn-sm btn-danger">Delete</button></td>' +
                      '</tr>';

            $("#NetworkFormBody").append(row);

        });
    });

};

app.EventListeners.test_network_btn = function() {
    $("#app_container").on('click', ".test_network_btn", function(){
        var networkID = $(this).closest('tr').children().first().html();

    });
};

app.EventListeners.delete_network_btn = function() {
    $("#app_container").on('click', ".delete_network_btn", function(){

    });
}