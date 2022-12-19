<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$permission = new Permissions($db);
$settings = new Settings($db);
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'settings');
$settings->read();
$permission->user_id = Session::get(Config::get('session/user_id'));
if (!$permission->role_check()){
    Redirect::to('../home/home.php');
}
?>

<!DOCTYPE html>
<head>
    <title>PMS-Settings</title>
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
                                    <span class="fa fa-gears"></span>  <b>Application Settings</b>
                                </a>
                            </header>
                            <div class="panel-body">
                                <form class="form-inline" enctype="multipart/form-data" method="post" role="form" id="settings_form" action="../process1.php">
                                    <div class="col-md-12">
                                        <div class="panel-body">

                                            <?php if (Session::exist('updated')){ ?>
                                            <div class="alert alert-success alert-dismissable">
                                                <strong><?=Session::flash('updated')?></strong>
                                            </div>
                                            <?php } ?>

                                            <?php if (Session::exist('error')){ ?>
                                                <div class="alert alert-danger alert-dismissable">
                                                    <strong><?=Session::flash('error')?></strong>
                                                </div>
                                            <?php } ?>
                                              <table class="table table-responsive">
                                                  <tbody>

                                                  <tr>
                                                      <th>Store Logo</th>
                                                      <td width="60%"><input name="logo" type="file"></td>
                                                      <td><img src="../../assets/products/<?=$settings->image?>" class="img-thumbnail" width="50px" height="50px" alt="Logo"></td>

                                                  </tr>


                                                  <tr>
                                                      <th>Store Name</th>
                                                      <td width="60%"><input type="text" name="store_name" value="<?=$settings->store_name?>" class="form-control"></td>
                                                      <td width="20%"><b><?=$settings->store_name?></b></td>
                                                  </tr>

                                                  <tr>
                                                      <th>Store Address</th>
                                                      <td width="60%"><input type="text" name="store_address" value="<?=$settings->address?>"  class="form-control"></td>
                                                      <td width="20%"><b><?=$settings->address?></b></td>
                                                  </tr>


                                                  <tr>
                                                      <th>Store Key</th>
                                                      <td width="60%"><input type="text" name="store_key" value="<?=$settings->store_key?>"  class="form-control"></td>
                                                      <td width="20%"><b><?=$settings->store_key?></b></td>
                                                  </tr>

                                                 <tr>
                                                      <th>Return Item after Sales have been made?</th>

                                                      <td width="60%"><input type="checkbox" <?php if($settings->return_status == 1) echo 'checked'; ?> name="return_sale"></td>
                                                      <td width="20%"><b> <?php if($settings->return_status == 1){ echo 'Yes'; }else{ echo  'No'; }?></b></td>
                                                  </tr>


                                                  <tr>
                                                      <th>Sale Out-of-stock Products</th>
                                                      <td width="60%"><input type="checkbox" <?php if($settings->sale_expired == 1) echo 'checked'; ?> name="sale_expired"></td>
                                                      <td width="20%"><b> <?php if($settings->sale_expired == 1){ echo 'Yes'; }else{ echo  'No'; }?></b></td>
                                                  </tr>



                                                  </tbody>
                                              </table>

                                        </div>
                                    </div>




                                    <div class="col-md-12">
                                        <button style="float: right; border-radius: 0" type="submit" class="btn btn-primary"><b>Save Settings</b></button>
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


