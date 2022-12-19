<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$permission = new Permissions($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
$employee = new Employee($db);
$result = $employee->read();
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'permission');
if (isset($_GET['employee_id'])){
    $permission->user_id = $_GET['employee_id'];
    $id = $permission->user_id;
    $permission->read();
    if (empty($permission->employee_id)) {
        die();
    }else{
        //get properties;
        $sales = json_decode($permission->sales,true);
        $product = json_decode($permission->product, true);
        $employees = json_decode($permission->employee, true);
        $customer = json_decode($permission->customer, true);
        $expiry = json_decode($permission->expired, true);
        $report = json_decode($permission->report, true);
        $expenses = json_decode($permission->expenses, true);
    }
}
if ($permission->role_check()){
    Redirect::to('../home/home.php');
}



?>

<!DOCTYPE html>
<head>
    <title>PMS-Permissions</title>
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
                        <section class="panel spinner" >
                            <header class="panel-heading">
                                <a style="float: left; margin-top: 10px; border-radius: 0" href="#" class="btn btn-primary">
                                    <span class="fa fa-lock"></span>  <b>Employee Permissions</b>
                                </a>
                            </header>
                            <div class="panel-body">
                                <form class="form-inline" role="form" id="permission_form">
                                    <div class="col-md-12">
                                            <header class="panel-heading">
                                               <button class="btn btn-primary" style="float: left; margin-top: 10px; border-radius: 0"><span class="fa fa-lock"></span> <b>Sales Permission</b></button>
                                            </header>
                                        <div class="panel-body">
                                            <input type="hidden" value="<?=$id?>" name="employee_id">
                                            <?php foreach ($sales as $sale){  ?>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Sales</b></label>
                                                <input style="float: right" type="checkbox" name="sales" <?php if ($sale['sales'] == '1') echo 'checked'?>>
                                            </div>

                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Sales Generate</b></label>
                                                <input style="float: right" type="checkbox" name="sales_add" <?php if ($sale['add'] == '1') echo 'checked'?>>
                                            </div>

                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Sales View</b></label>
                                                <input style="float: right" type="checkbox" name="sales_view" <?php if ($sale['view'] == '1') echo 'checked' ?> >
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px"  >
                                                <label class=""><b>Sales Edit</b></label>
                                                <input style="float: right" type="checkbox" name="sales_edit" <?php if ($sale['edit'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px" >
                                                <label class=""><b>Sales Delete</b></label>
                                                <input style="float: right" type="checkbox" name="sales_delete" <?php if ($sale['delete'] == '1') echo 'checked'?>>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <header class="panel-heading">
                                            <button class="btn btn-primary" style="float: left; margin-top: 10px; border-radius: 0"><span class="fa fa-lock"></span> <b>Product Permission</b></button>
                                        </header>
                                        <div class="panel-body">
                                            <?php  foreach ($product as $items){ ?>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Product</b></label>
                                                <input style="float: right" type="checkbox" name="product" <?php if ($items['product'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Product Add</b></label>
                                                <input style="float: right" type="checkbox" name="product_add" <?php if ($items['add'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Product View</b></label>
                                                <input style="float: right" type="checkbox" name="product_view" <?php if ($items['view'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Product Edit</b></label>
                                                <input style="float: right" type="checkbox" name="product_edit" <?php if ($items['edit'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Product Delete</b></label>
                                                <input style="float: right" type="checkbox" name="product_delete" <?php if ($items['delete'] == '1') echo 'checked'?>>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>




                                    <div class="col-md-12">
                                        <header class="panel-heading">
                                            <button class="btn btn-primary" style="float: left; margin-top: 10px; border-radius: 0"><span class="fa fa-lock"></span> <b>Employee Permission</b></button>
                                        </header>
                                        <div class="panel-body">
                                            <?php foreach ($employees as $staff){ ?>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Employee</b></label>
                                                <input style="float: right" type="checkbox" name="employee" <?php if ($staff['employee'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Employee Add</b></label>
                                                <input style="float: right" type="checkbox" name="employee_add" <?php if ($staff['add'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Employee View</b></label>
                                                <input style="float: right" type="checkbox" name="employee_view" <?php if ($staff['view'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Employee Edit</b></label>
                                                <input style="float: right" type="checkbox" name="employee_edit" <?php if ($staff['edit'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Employee Delete</b></label>
                                                <input style="float: right" type="checkbox" name="employee_delete" <?php if ($staff['delete'] == '1') echo 'checked'?>>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>



                                    <div class="col-md-12">
                                        <header class="panel-heading">
                                            <button class="btn btn-primary" style="float: left; margin-top: 10px; border-radius: 0"><span class="fa fa-lock"></span> <b>Customer Permission</b></button>
                                        </header>
                                        <div class="panel-body">
                                            <?php foreach ($customer as $buyer){ ?>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Customer</b></label>
                                                <input style="float: right" type="checkbox" name="customer" <?php if ($buyer['customer'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Customer Add</b></label>
                                                <input style="float: right" type="checkbox" name="customer_add" <?php if ($buyer['add'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Customer View</b></label>
                                                <input style="float: right" type="checkbox" name="customer_view" <?php if ($buyer['view'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Customer Edit</b></label>
                                                <input style="float: right" type="checkbox" name="customer_edit" <?php if ($buyer['edit'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Customer Delete</b></label>
                                                <input style="float: right" type="checkbox" name="customer_delete" <?php if ($buyer['delete'] == '1') echo 'checked'?>>
                                            </div>
                                            <?php  } ?>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <header class="panel-heading">
                                            <button class="btn btn-primary" style="float: left; margin-top: 10px; border-radius: 0"><span class="fa fa-lock"></span> <b>Expenses Permission</b></button>
                                        </header>
                                        <div class="panel-body">
                                            <?php foreach ($expenses as $expense){ ?>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Expenses</b></label>
                                                <input style="float: right" type="checkbox" name="expenses" <?php if ($expense['expenses'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Expenses Add</b></label>
                                                <input style="float: right" type="checkbox" name="expenses_add" <?php if ($expense['add'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Expenses View</b></label>
                                                <input style="float: right" type="checkbox" name="expenses_view" <?php if ($expense['view'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Expenses Edit</b></label>
                                                <input style="float: right" type="checkbox" name="expenses_edit" <?php if ($expense['edit'] == '1') echo 'checked'?>>
                                            </div>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Expenses Delete</b></label>
                                                <input style="float: right" type="checkbox" name="expenses_delete" <?php if ($expense['delete'] == '1') echo 'checked'?>>
                                            </div>
                                            <?php  } ?>
                                        </div>
                                    </div>



                                    <div class="col-md-12">
                                        <header class="panel-heading">
                                            <button class="btn btn-primary" style="float: left; margin-top: 10px; border-radius: 0"><span class="fa fa-lock"></span> <b>Expiry Permission</b></button>
                                        </header>
                                        <div class="panel-body">
                                            <?php foreach ($expiry as $expired){ ?>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Expired Product</b></label>
                                                <input style="float: right" type="checkbox" name="expiry"  <?php if ($expired['expire'] == '1') echo 'checked'?>>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <header class="panel-heading">
                                            <button class="btn btn-primary" style="float: left; margin-top: 10px; border-radius: 0"><span class="fa fa-lock"></span> <b>Report Permission</b></button>
                                        </header>
                                        <div class="panel-body">
                                            <?php foreach ($report as $reports){ ?>
                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Employee Report</b></label>
                                                <input style="float: right" type="checkbox" name="employee_report"  <?php if ($reports['employee'] == '1') echo 'checked'?>>
                                            </div>

                                            <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                <label class=""><b>Sales Report</b></label>
                                                <input style="float: right" type="checkbox" name="sales_report"  <?php if ($reports['sales'] == '1') echo 'checked'?>>
                                            </div>

                                                <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                    <label class=""><b>Customer Report</b></label>
                                                    <input style="float: right" type="checkbox" name="customer_report"  <?php if ($reports['customer'] == '1') echo 'checked'?>>
                                                </div>

                                                <div style="padding: 10px; border: 1px solid grey; box-shadow: 0px 0px 4px 0px grey; margin: 5px">
                                                    <label class=""><b>Product Report</b></label>
                                                    <input style="float: right" type="checkbox" name="product_report"  <?php if ($reports['product'] == '1') echo 'checked'?>>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <button style="float: right; border-radius: 0" type="submit" class="btn btn-primary">Save Permissions</button>
                                        <button style="float: right; border-radius: 0;" type="button" class="btn btn-info" onclick="reset_permission('permission_form')">Reset Permissions</button>
                                    </div>


                                </form>
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

