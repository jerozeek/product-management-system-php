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
                                        <span class="fa fa-info-circle"></span>  <strong>Summary Report</strong>
                                    </a>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="d">
                                        <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Product</th>
                                            <th>Quantity Sold</th>
                                            <th>Profit (<?=Session::get(Config::get('session/currency'))?>)</th>
                                            <th>Total Amount (<?=Session::get(Config::get('session/currency'))?>)</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td><b>Total:</b></td>
                                            <td style="font-weight: bold"></td>
                                            <td style="font-weight: bold"></td>
                                        </tr>
                                        </tfoot>
                                     <tbody>
                                        <?php
                                        $si = 1;
                                        while ($product_list = $sales_product->fetch(PDO::FETCH_ASSOC)){
                                            $si++;
                                            $details = $bill->sales_details($product_list['product']);
                                            while ($row = $details->fetch()){
                                            ?>
                                        <tr>
                                            <td><?=$si?></td>
                                            <td><?=$product_list['product']?></td>
                                            <td><?=$row['quantity']?></td>
                                            <td><?=$row['profit']?></td>
                                            <td><?=$row['subtotal']?></td>
                                        </tr>
                                        <?php } } ?>
                                        </tbody>
                                    </table>
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
<script>
    $('#d').DataTable( {
        responsive: true,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [ {extend: 'copy', className: 'btn-sm'},
            {extend: 'csv', title: 'Advances Records', className: 'btn-sm'},
            {extend: 'excel', title: 'Advances Records', className: 'btn-sm'},
            {extend: 'pdf', title: 'Advances Records', className: 'btn-sm'},
            {extend: 'print', className: 'btn-sm'} ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1:typeof i === 'number' ? i : 0;
            };
            //#----------- Total over this page------------------#
            paid = api.column(3, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            },0);
            total = api.column(4, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            },0);
            //#-----------ends of Total over this page------------------#
            // Update footer
            $( api.column(3).footer()).html(paid.toFixed(2));
            $( api.column(4).footer()).html(total.toFixed(2));
        }
    });
</script>

</body>
</html>

