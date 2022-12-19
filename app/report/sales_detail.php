<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$product = new Products($db);
$results = $product->get_all();
$employee = new Employee($db);
$employee_list = $employee->read();
$permission = new Permissions($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
$bill = new Sales($db);
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'report_sale');
if (!$permission->report_sales()){
    Redirect::to('../home/home.php');
}
?>
<!DOCTYPE html>
<head>
    <title>PMS-Sales Report</title>
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
                                <form class="form-inline" action="../process1.php" method="get">

                                    <div class="form-group">
                                        <label class="sr-only" for="start_date"></label>
                                        <b style="color: #9999A9">From:</b><input type="text" name="start_date" class="form-control start" id="filter-date" autocomplete="off" placeholder="" value="">
                                    </div>

                                    <div class="form-group">
                                        <label class="sr-only" for="end_date">To</label>
                                        <b style="color: #9999A9">To:</b><input type="text" name="end_date" class="form-control end" id="filter-date" autocomplete="off" placeholder="" value="">
                                    </div>

                                    <div class="form-group">
                                        <label class="sr-only" for="end_date">By Employee</label>
                                        <select class="form-control" name="by_employee">
                                            <option value="" selected>By Employee</option>
                                            <?php  if (!empty($employee_list)){ ?>
                                                <?php while ($employee_row = $employee_list->fetch(PDO::FETCH_ASSOC)){  ?>
                                            <option value="<?=$employee_row['username']?>"><?php echo $employee_row['username']?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label class="sr-only" for="end_date">By Product</label>
                                        <input list="product_by" value="By Product" class="form-control" name="by_product" id="by_product">
                                        <datalist id="product_by">
                                            <?php  if (!empty($results)){ ?>
                                                <?php while ($results_row = $results->fetch(PDO::FETCH_ASSOC)){  ?>
                                                    <option value="<?=$results_row['name']?>">
                                                <?php } } ?>
                                        </datalist>

                                    </div>




                                    <input type="hidden" name="sales_detail" value="sales_detail">
                                    <button class="btn btn-primary" type="submit" style="border-radius: 0px; margin-top: 2px;"><i class="fa fa-search"></i> Filter</button>

                                </form>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="detailed">
                                        <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Product</th>
                                            <th>Quantity Sold</th>
                                            <th>Profit (<?=Session::get(Config::get('session/currency'))?>)</th>
                                            <th>Total Amount (<?=Session::get(Config::get('session/currency'))?>)</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tfoot>


                                        <tbody>
                                     <?php

                                     if(Session::exist('start') && Session::exist('end')){
                                         $si = 0;
                                         $sales_product = $bill->product_by_date(Session::get('start'),Session::get('end'));
                                         foreach ($sales_product as $r){
                                             $details = $bill->sales_details_by_date($r['product'],Session::get('start'),Session::get('end'));
                                         foreach ($details as $row){
                                             $si++;
                                             ?>
                                             <tr>
                                                 <td><?=$si?></td>
                                                 <td><?=$r['product']?></td>
                                                 <td><?=$row['quantity']?></td>
                                                 <td><?=$row['profit']?></td>
                                                 <td><?=$row['subtotal']?></td>
                                                 <td><?=$row['created']?></td>
                                             </tr>
                                         <?php } } }else if (Session::exist('by_employee')){
                                         $si = 0;
                                         $sales_by_employee = $bill->sales_details_by_employee(Session::get('by_employee'));
                                         foreach ($sales_by_employee as $sales){
                                            $bill_id = $sales['id'];
                                            $sales_by_bill_id = $bill->product_by_employee($bill_id);
                                            foreach ($sales_by_bill_id as $value){
                                                $si++;
                                                ?>
                                             <tr>
                                                 <td><?=$si?></td>
                                                 <td><?=$value['product']?></td>
                                                 <td><?=$value['quantity']?></td>
                                                 <td><?=$value['profit']?></td>
                                                 <td><?=$value['subtotal']?></td>
                                                 <td><?=$value['created']?></td>
                                             </tr>
                                    <?php }}}else if(Session::exist('by_product')){
                                         $si = 0;
                                        $sales_by_product = $bill->sales_details_by_product(Session::get('by_product'));
                                        foreach ($sales_by_product as $value){ $si++; ?>
                                            <tr>
                                                <td><?=$si?></td>
                                                <td><?=$value['product']?></td>
                                                <td><?=$value['quantity']?></td>
                                                <td><?=$value['profit']?></td>
                                                <td><?=$value['subtotal']?></td>
                                                <td><?=$value['created']?></td>
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
        jQuery('#filter-date, #update_expiry, #search-from-date, #search-to-date').datetimepicker();
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
    $('#detailed').DataTable( {
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

<?php  Session::delete('start') ?>
<?php  Session::delete('end') ?>
<?php  Session::delete('by_employee') ?>
<?php  Session::delete('by_product') ?>
