<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$product = new Products($db);
$setting = new Settings($db);
$expired_status = $setting->sales_expired();
if ($expired_status == 1){
    //sales expires product....
    $results = $product->get_all();
}else{
    //dont sale expired product...
    $results = $product->get_by_expired();
}
$permission = new Permissions($db);
$customer = new Customers($db);
$customer_list = $customer->read();
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'sale');
if (!$permission->sales_menu()){
    Redirect::to('../home/home.php');
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

                            <form class="form-inline" role="form" style="float: right">
                               <div class="form-group">
                                    <input type="text" class="form-control" id="barcode" placeholder="" style="margin-top: -7px; border-radius: 0">
                                    <button style="margin-top: -7px; margin-left: -5px; border-radius: 0px" class="btn btn-success" id="barcode_search">
                                        <span class="fa fa-barcode"></span>  <strong>Barcode Scanner</strong>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <form method="post" action="../process1.php" class="col-md-12 col-sm-12 table-responsive">
                                    <table id="invoice" class="table table-striped">
                                        <thead>
                                        <tr class="bg-default">
                                            <th>Product Name</th>
                                            <th width="120">Category</th>
                                            <th width="50">Quantity</th>
                                            <th width="120">Price</th>
                                            <th>SubTotal</th>
                                            <th width="180">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" autocomplete="off" name="sale_product[]" class="form-control product_search" oninput="load_items(this,$(this).val())" list="products">
                                                <datalist id="products">
                                                    <?php  if (!empty($results)){ ?>
                                                    <?php  foreach ($results as $item){ ?>
                                                    <option value="<?=$item['name']?>">
                                                    <?php }} ?>
                                                </datalist>
                                            </td>
                                            <td>
                                                <input placeholder="" readonly name="sale_category[]" class="form-control"  selected id="sale_category">
                                            </td>
                                            <td><input type="text" required name="sale_quantity[]" id="quantity" autocomplete="off" class="totalCal form-control" placeholder=""  ></td>
                                            <td><input type="text" required name="sale_price[]" id="price" autocomplete="off" class="totalCal form-control" placeholder=""></td>
                                            <input type="hidden" name="profit[]" id="profit" autocomplete="off" class="form-control" placeholder="">
                                            <td><input type="text" name="subtotal[]"  readonly autocomplete="off" class="subtotal form-control" placeholder="" value="0.00"></td>
                                            <td>
                                                <div class="btn btn-group">
                                                    <button type="button" class="btn btn-sm btn-success addBtn">Add</button>
                                                    <button type="button" class="btn btn-sm btn-danger removeBtn">Remove</button>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>

                                      <tfoot>
                                        <tr class="bg-default">
                                            <td colspan="3"></td>
                                            <th class="text-right">Total</th>
                                            <th><input type="text" name="total" id="total" class="form-control" readonly required placeholder=""  value="0.00"></th>
                                            <td></td>
                                        </tr>

                                        <tr class="bg-default">
                                            <td colspan="3"></td>
                                            <th class="text-right">Grand Total</th>
                                            <th><input type="text" name="grand_total" readonly required autocomplete="off"  id="grand_total" class="paidDue form-control" placeholder="" value="0.00"></th>
                                            <td></td>
                                        </tr>


                                        <tr>
                                            <td colspan="3"></td>
                                            <th class="text-right">Paid</th>
                                            <td><input type="text" name="paid" id="paid" autocomplete="off"  class="paidDue form-control" required placeholder=""  value="0.00"></td>
                                            <td></td>
                                        </tr>

                                        <tr class="bg-default">
                                            <td colspan="3"></td>
                                            <th class="text-right">Due</th>
                                            <td><input type="text" name="due" id="due" autocomplete="off" class="paidDue form-control" required placeholder="" value="0.00"></td>
                                            <td></td>
                                        </tr>


                                        <tr class="bg-default">
                                            <td colspan="3"></td>
                                            <th class="text-right">Payment Methods</th>
                                            <th>
                                              <select class="form-control" name="payment_method" required>
                                                  <option value="Cash">Cash</option>
                                                  <option value="POS">POS</option>
                                                  <option value="Cash/POS">Cash/POS</option>
                                              </select>
                                            </th>
                                            <td></td>
                                        </tr>

                                        <tr class="bg-default">
                                            <td colspan="3"></td>
                                            <th class="text-right">Choose Customers</th>
                                            <th>
                                                <input class="form-control" type="text" list="customer" name="customer_id">
                                                <datalist id="customer">
                                                    <?php while ($row = $customer_list->fetch(PDO::FETCH_ASSOC)){  ?>
                                                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                                    <?php } ?>
                                                </datalist>
                                            </th>
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

