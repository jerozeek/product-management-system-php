<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$customer = new Customers($db);
$results = $customer->read();
$permission = new Permissions($db);
$bill = new Sales($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'customer');
if (!$permission->customer_menu()){
    Redirect::to('../home/home.php');
}
?>
<!DOCTYPE html>
<head>
    <title>PMS-Customers</title>
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
                        <section class="panel loader">
                            <header class="panel-heading">
                                <?php if ($permission->customer_add()){ ?>
                                    <a style="float: left; margin-top: 10px; border-radius: 0" href="#customerModal" data-toggle="modal" class="btn btn-primary">
                                        <span class="fa fa-plus"></span>  <strong>Add New Customer</strong>
                                    </a>
                                <?php  } ?>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="datatable">
                                        <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Customer Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Balance</th>
                                            <th>No. Purchase</th>
                                            <th>Total Amount</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $si = 0;
                                        if (!empty($results)){
                                            while ($item = $results->fetch(PDO::FETCH_ASSOC)){
                                                $si++;
                                                ?>
                                                <tr>
                                                    <td><?=$si?></td>
                                                    <td><?=$item['name']?></td>
                                                    <td><?=$item['email']?></td>
                                                    <td><?=$item['mobile']?></td>
                                                    <td>₦<?=number_format($bill->customer_balance($item['id']))?></td>
                                                    <td><?=number_format($bill->customer_sales_count($item['id']))?></td>
                                                    <td>₦<?=number_format($bill->total_amount($item['id']))?></td>

                                                    <td>
                                                        <?php if ($permission->customer_view()){ ?>
                                                        <label style="cursor: pointer" class="label label-success" onclick="customer_report('<?=$item['id']?>')"><i class="fa fa-eye"></i></label>
                                                        <?php } ?>
                                                        <?php if ($permission->customer_update()){ ?>
                                                        <label style="cursor: pointer" class="label label-info" onclick="customer_display('<?=$item['id']?>')"><i class="fa fa-edit"></i></label>
                                                        <?php  } ?>
                                                        <?php if ($permission->customer_delete()){  ?>
                                                        <label style="cursor: pointer" class="label label-danger" onclick="customer_delete('<?=$item['id']?>')"><i class="fa fa-trash"></i></label>
                                                        <?php  } ?>
                                                    </td>
                                                </tr>
                                            <?php }}  ?>

                                        </tbody>
                                    </table>


                                </div>

                            </div>
                        </section>
                    </div>
                </div>
                <a href="#customer_info" data-toggle="modal" class="btn btn-warning" id="display_customer_info" style="display: none;"></a>
                <a href="#update_customer_info" data-toggle="modal" class="btn btn-danger" id="update_customer" style="display: none"></a>


                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="customer_info" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Customer Info</h4>
                            </div>
                            <div class="modal-body">
                                <table class="table table-responsive">
                                    <tbody>
                                    <tr>
                                        <th>Customer Name</th>
                                        <td id="get_customer_name"></td>
                                    </tr>
                                    <tr>
                                        <th>Customer Email</th>
                                        <td id="get_customer_email"></td>
                                    </tr>
                                    <tr>
                                        <th>Customer Phone</th>
                                        <td id="get_customer_phone"></td>
                                    </tr>
                                    <tr>
                                        <th>Customer Address</th>
                                        <td id="get_customer_address"></td>
                                    </tr>
                                    <tr>
                                        <th>Due Balance</th>
                                        <td id="get_balance_in"></td>
                                    </tr>
                                    <tr>
                                        <th>Number of Purchase</th>
                                        <td id="number_of_purchase"></td>
                                    </tr>
                                    <tr>
                                        <th>Opened ON</th>
                                        <td id="created"></td>
                                    </tr>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="update_customer_info" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Update Customer Info</h4>
                            </div>
                            <div class="modal-body">
                                <form role="form">
                                    <div class="form-group">
                                        <label for="update_customer_id"><strong>Customer ID</strong></label>
                                        <input type="text" readonly class="form-control" id="update_customer_id" name="update_customer_id" placeholder="Enter email">
                                    </div>

                                    <div class="form-group">
                                        <label for="update_customer_name"><strong>Customer Name</strong></label>
                                        <input type="text" class="form-control" id="update_customer_name" name="update_customer_name" placeholder="Enter email">
                                    </div>


                                    <div class="form-group">
                                        <label for="update_customer_email"><strong>Customer Email</strong></label>
                                        <input type="text" autocomplete="off" class="form-control" id="update_customer_email" name="update_customer_email" placeholder="Enter email">
                                    </div>


                                    <div class="form-group">
                                        <label for="update_customer_mobile"><strong>Customer Mobile</strong></label>
                                        <input type="text" autocomplete="off" class="form-control" id="update_customer_mobile" name="update_customer_mobile" placeholder="Enter email">
                                    </div>


                                    <div class="form-group">
                                        <label for="update_customer_address"><strong>Customer Address</strong></label>
                                        <input type="text" autocomplete="off" class="form-control" id="update_customer_address" name="update_customer_mobile" placeholder="Enter email">
                                    </div>



                                    <button type="button" onclick="update_customer()" class="btn btn-primary" style="border-radius: 0">Update</button>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <!--modal call --->

                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="customerModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title"><strong>Add New Customer</strong></h4>
                            </div>
                            <div class="modal-body">

                                <form role="form">
                                    <div class="form-group">
                                        <label for="customer_name"><strong>Customer Name</strong></label>
                                        <input type="text" name="customer_name" autocomplete="off" class="form-control" id="customer_name" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label for="customer_email"><strong>Customer Email</strong></label>
                                        <input type="text" name="customer_email" autocomplete="off" class="form-control" id="customer_email" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label for="customer_phone"><strong>Customer Phone</strong></label>
                                        <input type="text" name="customer_phone" autocomplete="off" class="form-control" id="customer_phone" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label for="customer_address"><strong>Customer Address</strong></label>
                                        <input type="text" name="customer_address" autocomplete="off" class="form-control" id="customer_address" placeholder="">
                                    </div>

                                    <input type="hidden" id="token" value="<?=Token::generate()?>">
                                    <button type="button" onclick="add_customer()"  class="btn btn-success" style="border-radius: 0">Add Customer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--ends -->
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

