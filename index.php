<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <script src="js/Network.js"></script>

</head>

<body>
    <div class="container" id="app_container">
        <h2>Manage Networks</h2>

        <form class="form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="netWorkName">Network Name</label>
                <input type="text" name="NetworkName" class="form-control" id="netWorkName" placeholder="Network Name">
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="add_network_btn">Add</button>
        </form>

        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Network ID</th>
                    <th>Network Name</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="NetworkFormBody"></tbody>
        </table>
    </div>
</body>
</html>