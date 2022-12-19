<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'home');
$product = new Products($db);
$sale = new Sales($db);
$sale->count();
$product->count();
?>
<!DOCTYPE html>
<head>
<title>PEMS-Dashboard</title>
    <?php require_once '../require/head.php' ?>
</head>
<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<?php require '../require/header.php'?>
</header>
<!--header end-->
<!--sidebar start-->
<aside>
    <?php  require '../require/aside.php'?>
</aside>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
	<section class="wrapper">
        <!-- //market-->
        <div class="market-updates">
            <div class="col-md-3 market-update-gd">
                <div class="market-update-block clr-block-4">
                    <div class="col-md-4 market-update-right">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    </div>
                    <div class="col-md-8 market-update-left">
                        <h4>Items</h4>
                        <h3><?=$product->count?></h3>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>

            <div class="col-md-3 market-update-gd">
                <div class="market-update-block clr-block-2">
                    <div class="col-md-4 market-update-right">
                        <i class="fa fa-shopping-cart"> </i>
                    </div>
                    <div class="col-md-8 market-update-left">
                        <h4>Sales</h4>
                        <h3><?=$sale->count?></h3>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>


            <div class="col-md-3 market-update-gd">
                <div class="market-update-block clr-block-1">
                    <div class="col-md-4 market-update-right">
                        <i class="fa fa-usd" ></i>
                    </div>
                    <div class="col-md-8 market-update-left">
                        <h4>Store</h4>
                        <h3><?=Session::get(Config::get('session/currency')) . $sale->account?></h3>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>


            <div class="col-md-3 market-update-gd">
                <div class="market-update-block clr-block-3">
                    <div class="col-md-4 market-update-right">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="col-md-8 market-update-left">
                        <h4>Expired</h4>
                        <h3><?=$product->expired_count()?></h3>
                    </div>
                    <div class="clearfix"> </div>
                </div>
            </div>

            <div class="clearfix"> </div>
        </div>

        <?php require_once '../charts/task.php'?>
        <br>


        <?php require_once '../charts/sales.php' ?>


        <?php  require_once '../require/sales.php' ?>



    </section>
 <!-- footer -->
		<?php  require '../require/footer.php'?>
  <!-- / footer -->
</section>

<!--main content end-->
</section>
<script>
    $(document).ready(function () {

    });
</script>
<script src="../datatables/js/dataTables.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/jquery.dcjqaccordion.2.7.js"></script>
<script src="../js/scripts.js"></script>
<script src="../js/jquery.slimscroll.js"></script>
<script src="../js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="../js/jquery.fileupload.js"></script>
<script src="../js/script.js"></script>
<script src="../js/jquery.scrollTo.js"></script>

</body>
</html>
