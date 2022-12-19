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
Session::set(Config::get('session/active_menu'),'sale');
$bill_ID = $bill->load_bill_id();
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
                                <?php if ($permission->sales_add()){ ?>
                                    <a style="float: left; margin-top: 10px; border-radius: 0" href="sale_generate.php" class="btn btn-primary">
                                        <span class="fa fa-plus"></span>  <strong>Generate New Sales</strong>
                                    </a>
                                <?php } ?>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="sales_list">
                                        <thead>
                                        <tr>
                                            <th>Invoice ID</th>
                                            <th>Date</th>
                                            <th>Customer Name</th>
                                            <th>Employee Name</th>
                                            <th>Paid (<?=Session::get(Config::get('session/currency'))?>)</th>
                                            <th>Due (<?=Session::get(Config::get('session/currency'))?>)</th>
                                            <th>Amount (<?=Session::get(Config::get('session/currency'))?>)</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><b>Total: </b></td>
                                            <td style="font-weight: bold"></td>
                                            <td style="font-weight: bold"></td>
                                            <td style="font-weight: bold"></td>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php
                                        if (!empty($bill)){
                                            $si = 0;
                                            while ($item = $bill_ID->fetch(PDO::FETCH_ASSOC)){
                                                    $si++;
                                                $id = $item['id'];
                                                ?>
                                                <tr>
                                                    <td><?=$id?></td>
                                                    <td><?=$item['created']?></td>
                                                    <td>
                                                        <?php if ($item['customer_name'] == NULL || empty($item['customer_name'])){
                                                            echo 'Anonymous';
                                                        }else{
                                                            echo $item['customer_name'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?=$item['employee']?></td>
                                                    <td><?=number_format($item['paid'])?></td>
                                                    <td><?=number_format($item['due'])?></td>
                                                    <td><?=number_format($item['total'])?></td>
                                                    <td>
                                                        <?php if ($permission->sales_view()){ ?>
                                                        <a style="cursor: pointer" class="label label-info" href="sale_view.php?bill_id=<?=$id?>"><i class="fa fa-eye"></i></a>
                                                        <?php  } ?>
                                                        <?php if ($permission->sales_update() && $permission->check_sale_update_status()){ ?>
                                                            <a style="cursor: pointer" class="label label-success" href="sale_update.php?bill_id=<?=$id?>"><i class="fa fa-edit"></i></a>
                                                        <?php } ?>
                                                        <?php  if ($permission->sales_delete()){ ?>
                                                            <label style="cursor: pointer" class="label label-danger" onclick="sales('<?=$item['id']?>','delete')"><i class="fa fa-trash"></i></label>
                                                        <?php }  ?>
                                                    </td>
                                                </tr>
                                            <?php }} ?>

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
    $('#sales_list').DataTable( {
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
            paid = api.column(4, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            },0);
            total = api.column(5, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            },0);
            due = api.column(6, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            },0);
            //#-----------ends of Total over this page------------------#
            // Update footer
            $( api.column(4).footer()).html(paid.toFixed(2));
            $( api.column(5).footer()).html(total.toFixed(2));
            $( api.column(6).footer()).html(due.toFixed(2));
        }
    });
</script>
</body>
</html>

