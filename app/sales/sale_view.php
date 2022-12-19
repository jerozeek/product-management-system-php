<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$product = new Products($db);
$results = $product->get_all();
$permission = new Permissions($db);
$bill = new Sales($db);
$settings = new Settings($db);
$settings->read();
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'sale');
if (isset($_GET['bill_id'])){
    $bill_details = $bill->load_bill_details($_GET['bill_id']);
    $bill_ID = $bill->load_bill_id();
    $bill->bill_id = $_GET['bill_id'];
    $r = $bill->validate_bill_details();
    while ($invoice = $r->fetch(PDO::FETCH_ASSOC)){
        $grand_total = $invoice['total'];
        $payment_method = $invoice['payment_method'];
        $id = $invoice['id'];
        $paid = $invoice['paid'];
        $due = $invoice['due'];
        $customer = $invoice['customer_name'];
    }
}else{
    die();
}
if (!$permission->sales_menu()){
    Redirect::to('../home/home.php');
}
?>
<!DOCTYPE html>
<head>
    <title>PEMS-Sales</title>
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
                                <a style="float: left; margin-top: 10px; border-radius: 0" href="sale_generate.php" class="btn btn-primary">
                                    <span class="fa fa-plus"></span>  <strong>Generate New Sales</strong>
                                </a>
                                <button style="float: left; margin-top: 10px; border-radius: 0" type="button" onclick="printContent('PrintMe')" class="btn btn-danger"><i class="fa fa-print"></i></button>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12" id="PrintMe">
                                    <?php echo Session::flash('success')?>
                                    <th width="20%" class="text-center" style="text-align: center">
                                        <strong style="line-height:60px;margin-left: 45%"><img width="60px" height="60px" style="border-radius: 50%" class="img-circle" src="../../assets/products/<?=$settings->image?>"></strong>
                                        <p style="line-height:60px;margin-left: 40%;margin-top: -20px; margin-right: 45% text-align: center;"><b><?=$settings->store_name?></b></p>
                                        <p style="margin-left: 35%; margin-top: -20px; margin-right: 45% text-align: center"><b><?=$settings->address?></b></p>
                                    </th>
                                <table id="invoice" class="table table-striped">
                                    <thead>
                                    <tr class="bg-primary">
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th width="160" class="text-center">Subtotal</th>
                                    </tr>
                                    </thead>

                                    <!-- showing data -->
                                    <tbody>
                                    <?php
                                    while ($value = $bill_details->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                            <tr>
                                                <td><?php echo $value['product'] ?></td>
                                                <td><?php echo $value['quantity']; ?></td>
                                                <td><?php echo Session::get(Config::get('session/currency')). sprintf('%0.2f', $value['price']); ?></td>
                                                <td class="text-center"><?php echo Session::get(Config::get('session/currency')). sprintf('%0.2f', $value['subtotal']) ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                    <!-- ends of showing data -->

                                    <tfoot>
                                    <tr class="bg-default">
                                        <td colspan="2"></td>
                                        <th>Total</th>
                                        <th class="text-center"><?php echo Session::get(Config::get('session/currency')). sprintf('%0.2f', $grand_total) ?></th>
                                    </tr>

                                    <tr class="bg-default">
                                        <td colspan="2"></td>
                                        <th>Paid Total</th>
                                        <th class="text-center"><?php echo Session::get(Config::get('session/currency')). sprintf('%0.2f', $paid) ?></th>
                                    </tr>


                                    <tr class="bg-default">
                                        <td colspan="2"></td>
                                        <th>Due Total</th>
                                        <th class="text-center"><?php echo Session::get(Config::get('session/currency')). sprintf('%0.2f', $due) ?></th>
                                    </tr>

                                    <tr class="bg-default">
                                        <td colspan="2"></td>
                                        <th>Payment Method</th>
                                        <th class="text-center"><?php echo $payment_method ?></th>
                                    </tr>

                                    <?php if (!empty($customer) || $customer !== NULL){ ?>
                                    <tr class="bg-default">
                                        <td colspan="2"></td>
                                        <th>Customer</th>
                                        <th class="text-center"><?php echo $customer ?></th>
                                    </tr>
                                    <?php  } ?>

                                    </tfoot>
                                </table>
                                    <label><b>Invoice ID: <?=$id?></b></label>
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
<script>
    /*jslint browser:true*/
    /*global jQuery, document*/
    jQuery(document).ready(function () {
        'use strict';
        jQuery('#expiry, #update_expiry, #search-from-date, #search-to-date').datetimepicker();
    });
</script>
<script>
    $(document).ready(function () {
        $('#datatable').DataTable({});
    });
</script>
<script src="../datatables/js/dataTables.min.js"></script>
<!--datepicker -->
<script src="../datepicker/jquery.datetimepicker.full.min.js"></script>

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

</body>
</html>
