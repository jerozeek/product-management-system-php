<?php
require_once 'autoload.php';
$database = new DB();
$db = $database->connect();
$installation = new Installation($db);
$installation->installation_set();
?>
<!DOCTYPE html>
<html>
<head>
    <title>PMS-INSTALLATION</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Production Management System For Sales,Inventory Control, Market Analysis, and Transparency" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- bootstrap-css -->
    <link rel="stylesheet" href="app/css/bootstrap.min.css" >
    <style>
        body{
            background-image: url("assets/images/shopping-1232944_1920.jpg");
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <!-- //bootstrap-css -->
    <!-- Custom CSS -->

    <!-- font CSS -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <!-- font-awesome icons -->
    <link rel="stylesheet" href="app/css/font.css" type="text/css"/>
    <link href="app/css/font-awesome.css" rel="stylesheet">
    <!-- //font-awesome icons -->
    <script src="app/js/jquery2.0.3.min.js"></script>
</head>
<body>

    <div class="container">
        <div class="col-md-12">
            <div class="frame" style="background-color:white;border-radius:10px;border: 1px solid grey; box-shadow: 0 0 8px 0 grey; opacity: 0.9;   height: 450px; width: 1000px; margin: 100px 0 0 80px; text-align: center">
                <div class="col-md-12">
                    <br><br><br>
                    <img src="assets/products/logo.png" class="img-responsive" width="100px" height="100px" style="border: 1px solid grey; border-radius: 50%;padding: 20px; margin-left: 45%">
                    <br><br>
                    <p><b>Installing Product Management System for the first time. This will take a few minutes</b></p>
                    <br>
                    <button id="install_app" onclick="begin_installation()" class="btn btn-primary" style="border-radius: 0px">Begin Installation</button>
                    <div class="progress" style="display: none"  id="progress">
                        <div class="progress-bar" id="progress_loader" role="progressbar" style="width: 5%;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">5%</div>
                    </div>
                    <strong id="install_message"></strong><br>
                    <button class="btn btn-primary" id="proceed" style="border-radius: 0px; display: none; float: right"><a style="text-decoration: none; color: white" href="index.php">Proceed to App</a> <span class="fa fa-arrow-right"></span></button>
                    <small id="error_installation"></small>
                    <br><br><br>
                    <p><b>Version 1.0</b></p>
                </div>
            </div>
        </div>
    </div>
<script>
    function begin_installation() {
        $('#install_app').css('display','none');
        $('#progress').css('display','block');
        application();
    }
    function application() {
        //check for the database file........if installed or not
        $.ajax({
            url:'bench.php',
            dataType:'json',
            method:'post',
            success:function (data) {
                if (data.success === true){
                    $('#progress_loader').attr('aria-valuenow','100').css('width','100%').val('100%').text('100%');
                    $('#install_message').text('Installing was Completed Successfully.');
                    $('#proceed').css('display','block');
                }else{
                    //error found
                    $('#error_installation').text(data.result);
                }
            }
        })
    }
</script>
</body>
</html>

