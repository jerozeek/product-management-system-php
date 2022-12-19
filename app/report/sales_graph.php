<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$product = new Products($db);
$results = $product->get_all();
$permission = new Permissions($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
$bill = new Sales($db);
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'report_sale');
$sales_product = $bill->sales_item();
$balance = new Balance($db);
if (!$permission->report_sales()){
    Redirect::to('../home/home.php');
}
?>
<!DOCTYPE html>
<head>
    <title>PMS-Sales</title>
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
            <div class="form-w3layouts loader">
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">
                                <a style="float: left; margin-top: 10px; border-radius: 0" href="#" class="btn btn-primary">
                                    <span class="fa fa-info-circle"></span>  <strong>Graphical Report</strong>
                                </a>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <form role="form" style="margin-top: 50px">

                                            <div class="form-group">
                                            <select class="form-control">
                                                <?php foreach(json_decode($balance->last_ten_years()) as $value){?>
                                                <option><?=$value?></option>
                                                <?php } ?>
                                            </select>
                                            </div>

                                            <div class="form-group">
                                                <select class="form-control">
                                                    <?php $i = 0;  foreach(json_decode($balance->all_month()) as $month){ $i++ ?>
                                                    <option value="0<?=$i?>"><?=$month?></option>
                                                    <?php  } ?>
                                                </select>
                                            </div>


                                            <div class="form-group">
                                                <select class="form-control">
                                                    <?php $i = 0;  foreach(json_decode($balance->all_day()) as $day){ $i++ ?>
                                                    <option value="0<?=$i?>"><?=$day?></option>
                                                    <?php  } ?>
                                                </select>
                                            </div>

                                            <div>
                                                <button class="btn btn-primary btn-block" style="border-radius: 0">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!--load the graph here--->
                                    <div class="col-md-8" style="float: right">
                                        <div id="bar-example"></div>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

        </section>
        <!-- footer -->
        <?php  require '../require/footer.php'?>
        <!-- / footer -->
    </section>
    <!--main content end-->
</section>

<script src="../js/bootstrap.js"></script>
<script src="../js/jquery.dcjqaccordion.2.7.js"></script>
<script src="../js/scripts.js"></script>
<script src="../js/modify.js"></script>
<script src="../js/jquery.slimscroll.js"></script>
<script src="../js/jquery.nicescroll.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="../js/jquery.fileupload.js"></script>
<script src="../js/script.js"></script>
<script src="../js/jquery.scrollTo.js"></script>
<script>
    Morris.Bar({
        element: 'bar-example',
        data: [
            { y: '2006', a: 100, b: 90 },
            { y: '2007', a: 75,  b: 65 },
            { y: '2008', a: 50,  b: 40 },
            { y: '2009', a: 75,  b: 65 },
            { y: '2010', a: 50,  b: 40 },
            { y: '2011', a: 75,  b: 65 },
            { y: '2012', a: 100, b: 90 }
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B']
    });
</script>

</body>
</html>

