<?php
require_once 'autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$installation = new Installation($db);
$installation->installation_status();
//process Login
if (isset($_POST['username'],$_POST['password'])){
    if (Token::check($_POST['token'])) {
        $login->username = $_POST['username'];
        $login->password = md5($_POST['password']);
        $result = $login->login();
        $num = $result->rowCount();
        if ($num > 0) {
            //login was successful
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                Session::set(Config::get('session/user_id'), $row['id']);
                //update last login date
                $login->update_last_login();
                //verify the user role and set a session for it...
                $login->roles();
                //now redirect
                Redirect::to('app/home/home.php');
            }
        } else {
            Session::flash('exception', "<div class='alert alert-danger wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Error: </strong> Invalid Username and Password</div>");
        }
    }else{
        Session::flash('exception', "<div class='alert alert-danger wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Error: </strong> Token have expired</div>");

    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PMS-LOGIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Production Management System For Sales,Inventory Control, Market Analysis, and Transparency" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- bootstrap-css -->
    <link rel="stylesheet" href="app/css/bootstrap.min.css" >
    <!-- //bootstrap-css -->
    <!-- Custom CSS -->
    <link href="app/css/style.css" rel='stylesheet' type='text/css' />
    <link href="app/css/style-responsive.css" rel="stylesheet"/>
    <!-- font CSS -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <!-- font-awesome icons -->
    <link rel="stylesheet" href="app/css/font.css" type="text/css"/>
    <link href="app/css/font-awesome.css" rel="stylesheet">
    <!-- //font-awesome icons -->
    <script src="app/js/jquery2.0.3.min.js"></script>
</head>
<body>
<div class="log-w3">
    <div class="w3layouts-main">
        <img src="assets/products/logo.png" id="img_logo" class="img-responsive col-md-offset-5" width="80px" height="80px"><br>
        <b class="col-md-offset-2" style="color: black">PRODUCT MANAGEMENT SYSTEM</b><hr>
        <?php  if (Session::exist('exception')) echo Session::flash('exception')?>
        <form action="#" method="post">
            <input type="text" class="ggg" name="username" placeholder="USERNAME" required="">
            <input type="password" class="ggg" name="password" placeholder="PASSWORD" required="">
            <input type="hidden" name="token" value="<?=Token::generate()?>">
            <div class="clearfix"></div>
            <input type="submit" value="Login" name="login">
        </form>
        <p>PMS Version 2.0.1 <a target="_blank" href="https://quicksales.com.ng/update">Check for Update</a></p>
    </div>
</div>
</body>
<script src="app/js/bootstrap.js"></script>
<script src="app/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="app/js/scripts.js"></script>
<script src="app/js/jquery.slimscroll.js"></script>
<script src="app/js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]>
<script language="javascript" type="text/javascript" src="app/js/flot-chart/excanvas.min.js"></script>
<![endif]-->
<script src="app/js/jquery.scrollTo.js"></script>
<script>
    $(document).ready(function () {
        $('#login_form').each(function () {
            $(this).find(':input').val('');
        },);

    })
</script>

</html>