<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$product = new Products($db);
$results = $product->get_all();
$permission = new Permissions($db);
$sales = new Sales($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'sale');
if (!$permission->sales_update()){
    Redirect::to('../home/home.php');
}elseif (!$permission->check_sale_update_status()){
    Redirect::to('../home/home.php');
}
if (isset($_GET['bill_id'])){
    //validate the bill id
    $sales->bill_id = $_GET['bill_id'];
    if ($sales->validate_bill_id()){
     $details = $sales->load_bill_details($sales->bill_id);
     $r = $sales->validate_bill_details();
     while ($invoice = $r->fetch(PDO::FETCH_ASSOC)){
         $grand_total = $invoice['total'];
         $paid = $invoice['paid'];
         $due = $invoice['due'];
     }
    }else{
        //display error 404
        die();
    }
}else{
   //display error 404
    die();
}
?>

<!DOCTYPE html>
<head>
    <title>PEMS-Product</title>
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
            <div class="col-sm-12">
                <div  class="panel panel-default thumbnail">
                    <div class="panel-heading no-print">
                        <a style="float: left; margin-top: 10px; border-radius: 0" href="sale_list.php" class="btn btn-primary">
                            <span class="fa fa-eye"></span>  <strong>View Sales</strong>
                        </a>

                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <form method="post" action="../process1.php" class="col-md-12 col-sm-12 table-responsive">
                                <input type="hidden" name="bill_id" value="<?=$sales->bill_id?>">
                                <table id="invoice" class="table table-striped">
                                    <thead>
                                    <tr class="bg-default">
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th width="50">Quantity</th>
                                        <th width="120">Price</th>
                                        <th width="120">SubTotal</th>
                                        <th width="180">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    if (!empty($details)){
                                        while ($row = $details->fetch(PDO::FETCH_ASSOC)){
                                            extract($row);
                                        ?>
                                    <tr>
                                        <td>
                                            <input type="text" autocomplete="off" name="return_product[]" id="product" value="<?=$product?>" class="form-control product_search">
                                        </td>
                                        <td>
                                            <input placeholder="" readonly name="sale_category[]" class="form-control"  selected id="sale_category">
                                        </td>
                                        <td><input type="text" required name="return_quantity[]" id="quantity" autocomplete="off" class="totalCal form-control" placeholder="" value="<?=$quantity?>"  ></td>
                                        <td><input type="text" required name="return_price[]" id="price" autocomplete="off" class="totalCal form-control" placeholder="" value="<?=$price?>"></td>
                                        <input type="hidden" required name="profit[]" id="profit" autocomplete="off" class="form-control" placeholder="" value="<?=$profit?>">
                                        <td><input type="text" name="subtotal[]"  readonly autocomplete="off" class="subtotal form-control" placeholder="" value="<?=$subtotal.'.00'?>"></td>
                                        <td>
                                            <div class="btn btn-group">
                                                <button type="button" class="btn btn-sm btn-danger removeItem" onclick="return_items(this,'<?=$id?>')">Return Item</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php  } } ?>
                                    </tbody>

                                    <tfoot>
                                    <tr class="bg-default">
                                        <td colspan="3"></td>
                                        <th class="text-right">Total</th>
                                        <th><input type="text" name="total" id="total" class="form-control" readonly required placeholder=""  value="<?=$grand_total.'.00'?>"></th>
                                        <td></td>
                                    </tr>

                                    <tr class="bg-default">
                                        <td colspan="3"></td>
                                        <th class="text-right">Grand Total</th>
                                        <th><input type="text" name="grand_total" readonly required autocomplete="off"  id="grand_total" class="paidDue form-control" placeholder="" value="<?=$grand_total.'.00'?>"></th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <th class="text-right">Paid</th>
                                        <td><input type="text" name="paid" id="paid" autocomplete="off"  class="paidDue form-control" required placeholder=""  value="<?=$paid?>.00"></td>
                                        <td></td>
                                    </tr>

                                    <tr class="bg-default">
                                        <td colspan="3"></td>
                                        <th class="text-right">Due</th>
                                        <td><input type="text" name="due" id="due" autocomplete="off" class="paidDue form-control" required placeholder="" value="<?=$due?>.00"></td>
                                        <td></td>
                                    </tr>



                                    <tr>
                                        <td colspan="3">
                                        </td>
                                        <td><button type="reset" class="btn btn-info btn-block">Reset</button></td>
                                        <td><button class="btn btn-success btn-block" id="save">Save</button></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
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
<script src="../js/script.js"></script>


</body>
</html>


