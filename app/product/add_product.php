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
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'product');
if (!$permission->product_menu()){
    Redirect::to('../home/home.php');
}
?>
<!DOCTYPE html>
<head>
    <title>PMS-Product</title>
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
                                <?php if ($permission->product_add()){  ?>
                                <a style="float: left; margin-top: 10px; border-radius: 0" href="#myModal" data-toggle="modal" class="btn btn-primary">
                                    <span class="fa fa-plus"></span>  <strong>Add New Product</strong>
                                </a>
                                    <a style="float: left; margin-top: 10px; border-radius: 0" href="#myModal-3" data-toggle="modal" class="btn btn-success">
                                    <span class="fa fa-plus"></span>  <strong>Add Category</strong>
                                </a>
                                <?php  } ?>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="datatable">
                                        <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Quantity</th>
                                           <!-- <th>Cost Price(₦)</th>  -->
                                            <th>Selling Price(₦)</th>
                                            <!-- <th>Expiry Date</th> -->
                                            <th>Amount Generated(₦)</th>
                                            <th>Status</th>
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
                                            <td><?=$item['category']?></td>
                                            <td><?=$item['quantity']?></td>
                                            <!--<td>₦<?=number_format($item['cost_price'])?></td> -->
                                            <td>₦<?=number_format($item['selling_price'])?></td>
                                           <!-- <td><?=$item['expiry']?></td> -->
                                            <td>₦<?=$bill->amount_generated($item['name'])?></td>
                                            <td>
                                                <?php
                                                if ($item['quantity'] < 1) {
                                                    echo "<label class='label label-danger'>out of stock</label>";
                                                }else{
                                                    echo "<label class='label label-success'>in stock</label>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($permission->product_view()){ ?>
                                                <label style="cursor: pointer" class="label label-success" onclick="product('<?=$item['id']?>','view')"><i class="fa fa-eye"></i></label>
                                                <?php } ?>
                                                <?php if ($permission->product_update()){ ?>
                                                <label style="cursor: pointer" class="label label-info" onclick="product('<?=$item['id']?>','edit')"><i class="fa fa-edit"></i></label>
                                                <?php  }?>
                                                <?php if ($permission->product_delete()){ ?>
                                                <label style="cursor: pointer" class="label label-danger" onclick="product('<?=$item['id']?>','delete')"><i class="fa fa-trash"></i></label>
                                                <?php }  ?>
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


                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title"><strong>Add New Product</strong></h4>
                            </div>
                            <div class="modal-body">

                                <form role="form" is="product_form">
                                    <div class="form-group">
                                        <label for="product"><strong>Product Name</strong></label>
                                        <input type="text" name="product" autocomplete="off" class="form-control" id="product" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity"><strong>Product Category</strong></label>
                                       <select class="form-control" id="category_name">
                                           <?php if (!empty($product->get_category())){
                                               foreach ($product->get_category() as $category){
                                               ?>
                                           <option><?=$category['name']?></option>
                                           <?php }} ?>
                                       </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="quantity"><strong>Product Quantity</strong></label>
                                        <input type="text" autocomplete="off" class="form-control" name="quantity" id="quantity" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label for="price"><strong>Cost Price(₦)</strong></label>
                                        <input type="text" autocomplete="off" class="form-control" id="c_price" name="c_price" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="price"><strong>Selling Price(₦)</strong></label>
                                        <input type="text" autocomplete="off" class="form-control" id="s_price" name="s_price" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label for="manufacturer"><strong>Product Manufacturer</strong></label>
                                        <input type="text" autocomplete="off" class="form-control" id="manufacturer" name="manufacturer" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label for="manufacturer"><strong>Expiry Date</strong></label>
                                        <input type="text" readonly autocomplete="off" class="form-control" id="expiry" name="filter-date" placeholder="">
                                    </div>

                                    <input type="hidden" id="token" value="<?=Token::generate()?>">


                                    <button type="button" onclick="add_product()" id="product_new" class="btn btn-success">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!--Add Category --->
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-3" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title"><strong>Add Product Category</strong></h4>
                            </div>
                            <div class="modal-body">

                                <form role="form" is="product_form">
                                    <div class="form-group">
                                        <label for="product"><strong>Category Name</strong></label>
                                        <input type="text" name="category" autocomplete="off" class="form-control" id="category" placeholder="">
                                    </div>

                                    <button type="button" onclick="create_category()" id="category_new" class="btn btn-success">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#myModal-1" data-toggle="modal" class="btn btn-warning" id="display" style="display: none;"></a>
                <a href="#myModal-2" data-toggle="modal" class="btn btn-danger" id="update_view" style="display: none"></a>
                <!--  <div class="text-center">
                                       <a href="#myModal" data-toggle="modal" class="btn btn-success">
                                           Form in Modal
                                       </a>
                                       <a href="#myModal-1" data-toggle="modal" class="btn btn-warning">
                                           Form in Modal 2
                                       </a>
                                       <a href="#myModal-2" data-toggle="modal" class="btn btn-danger">
                                           Form in Modal 3
                                       </a>
                                   </div> -->

                                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-1" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Product Info</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-responsive">
                                                        <tbody>
                                                        <tr>
                                                            <th>Product ID</th>
                                                            <td id="get_id"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <td id="get_name"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Category</th>
                                                            <td id="get_category"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Product Quantity</th>
                                                            <td id="get_quantity"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Cost Price</th>
                                                            <td id="get_cost"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Selling Price</th>
                                                            <td id="get_sell"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Manufacturer</th>
                                                            <td id="get_manufacturer"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Expiry</th>
                                                            <td id="get_expiry"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td id="get_status"></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Barcode</th>
                                                            <td id="barcode"></td>
                                                        </tr>

                                                        </tbody>
                                                    </table>

                                                </div>

                                            </div>
                                        </div>
                                    </div>



                                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-2" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                    <h4 class="modal-title"><i class="fa fa-info-circle"></i> Update Product</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form role="form">
                                                        <div class="form-group">
                                                            <label for="update_id">Product ID</label>
                                                            <input type="text" readonly class="form-control" id="update_id" name="update_product" placeholder="Enter email">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="update_product">Product Name</label>
                                                            <input type="text" class="form-control" id="update_product" name="update_product" placeholder="Enter email">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="quantity"><strong>Product Category</strong></label>
                                                            <select class="form-control" id="update_category">
                                                                <?php if (!empty($product->get_category())){
                                                                    foreach ($product->get_category() as $category){
                                                                        ?>
                                                                        <option><?=$category['name']?></option>
                                                                    <?php }} ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="update_quantity">Product Quantity</label>
                                                            <input type="number" autocomplete="off" class="form-control" id="update_quantity" name="update_quantity" placeholder="Enter email">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="update_cost">Cost Price</label>
                                                            <input type="text" autocomplete="off" class="form-control" id="update_cost" placeholder="Enter email">
                                                        </div>

                                                          <div class="form-group">
                                                            <label for="update_sales">Selling Price</label>
                                                            <input type="text" autocomplete="off" class="form-control" id="update_sales" placeholder="Enter email">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="update_manufacturer">Manufacturer</label>
                                                            <input type="text" autocomplete="off" class="form-control" id="update_manufacturer" placeholder="Enter email">
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="update_expiry"><strong>Expiry Date</strong></label>
                                                            <input type="text" autocomplete="off" class="form-control" id="update_expiry" name="update_expiry" placeholder="">
                                                        </div>



                                                        <button type="button" onclick="product_update()" class="btn btn-success">Update</button>
                                                    </form>

                                                </div>

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
<script src="../js/jquery.fileupload.js"></script>
<script src="../js/script.js"></script>
<script src="../js/jquery.scrollTo.js"></script>

</body>
</html>

