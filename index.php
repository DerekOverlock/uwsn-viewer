<?php
//header('Content-type: text/plain');

require_once __DIR__ . "/config.inc.php";
require_once PHP_LIB . "/User.php";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    </head>

    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Brand</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Link</a></li>
                        <li><a href="#">Link</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Link</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>


    <div style="margin-top:50px; margin-bottom: 10px">------------------------------------------------------------------------------</div>
    <!-- section for node registration form-->
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h4>Register a Node</h4>
            <form role="form">

                <div class="form-group">
                    <label for="nodeName">Node Name</label>
                    <input type="text" class="form-control" id="nodeName" name="nodeName" placeholder="Enter Node Name">
                </div>
                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input type="number" class="form-control" id="latitude" name="latitude" placeholder="Enter Latitude">
                </div>
                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="number" class="form-control" id="longitude" name="longitude" placeholder="Enter Longitude">
                </div>
                <div class="form-group">
                    <label for="serialNum">Serial Number</label>
                    <input type="text" class="form-control" id="serialNum" name="serialNum" placeholder="Enter Longitude">
                </div>
                <div class="form-group">
                    <label for="ownedBy">Owned By</label>
                    <select class="form-control input-sm" id="ownedBy" name="ownedBy">
                        <option>University of Connecticut</option>
                        <option>University of Southern Califonia</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>

    <div style="margin-top:50px; margin-bottom: 10px">--------------------------------------------------------------------------</div>
    <div class="row">
        <div class="col-md-2 col-md-offset-1" >
            <a href="#" class="thumbnail" style="position: relative; padding: 0px; display: inline-block">
                <img data-src="holder.js/178x178" src="img/pic_holder.png" >
                <div style="position: absolute; left: 0px; bottom: 0px; padding:4px; z-index: 999; opacity: 0.5; background: black; color: white; height: 40px; width: 100%;">hello</div>
            </a>
        </div>
        <div class="col-md-2" >
            <a href="#" class="thumbnail" style="position: relative; padding: 0px; display: inline-block">
                <img data-src="holder.js/178x178" src="img/pic_holder.png" >
                <div style="position: absolute; left: 0px; bottom: 0px; padding:4px; z-index: 999; opacity: 0.5; background: black; color: white; height: 40px; width: 100%;">hello</div>
            </a>
        </div>
        <div class="col-md-2" >
            <a href="#" class="thumbnail" style="position: relative; padding: 0px; display: inline-block">
                <img data-src="holder.js/178x178" src="img/pic_holder.png" >
                <div style="position: absolute; left: 0px; bottom: 0px; padding:4px; z-index: 999; opacity: 0.5; background: black; color: white; height: 40px; width: 100%;">hello</div>
            </a>
        </div>
        <div class="col-md-2" >
            <a href="#" class="thumbnail" style="position: relative; padding: 0px; display: inline-block">
                <img data-src="holder.js/178x178" src="img/pic_holder.png" >
                <div style="position: absolute; left: 0px; bottom: 0px; padding:4px; z-index: 999; opacity: 0.5; background: black; color: white; height: 40px; width: 100%;">hello</div>
            </a>
        </div>
        <div class="col-md-2" >
            <a href="#" class="thumbnail" style="position: relative; padding: 0px; display: inline-block">
                <img data-src="holder.js/178x178" src="img/pic_holder.png" >
                <div style="position: absolute; left: 0px; bottom: 0px; padding:4px; z-index: 999; opacity: 0.5; background: black; color: white; height: 40px; width: 100%;">hello</div>
            </a>
        </div>
    </div>


    <div style="margin-top:50px; margin-bottom: 10px">--------------------------------------------------------------------------</div>
    <div class="row">
        <div class="col-md-3 col-md-offset-2">
            <div class="thumbnail">
                <img data-src="holder.js/300x200" src="img/pic_holder.png">
                <div class="caption">
                    <h4>Time: Jan-01-2015</h4>
                    <h5>Taken by: Some Node</h5>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <h4>New Notes</h4>
            <form role="form">
                <div class="form-group">
                    <label for="picNotes">Add notes to the image:</label>
                    <textarea class="form-control" id="picNotes" name="picNotes" rows="3" placeholder="Enter your description for this image"></textarea>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>

    <div style="margin-top:50px; margin-bottom: 10px">--------------------------------------------------------------------------</div>
    <div style="margin-top:50px; margin-bottom: 10px">--------------------------------------------------------------------------</div>
    </body>
</html>