<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$permission = new Permissions($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'employee');
$employee = new Employee($db);
$result = $employee->read();
if (!$permission->employee_menu()){
    Redirect::to('../home/home.php');
}
?>
<!DOCTYPE html>
<head>
    <title>PEMS-Employee</title>
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
                                <?php if ($permission->employee_add()){ ?>
                                <a style="float: left; margin-top: 10px; border-radius: 0" href="#employeeModal" data-toggle="modal" class="btn btn-primary">
                                    <span class="fa fa-plus"></span>  <strong>Add New Employee</strong>
                                </a>
                                <?php } ?>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="datatable">
                                        <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Last Login</th>
                                            <th>Number of Sales</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $si = 0;
                                            while ($records = $result->fetch(PDO::FETCH_ASSOC)){
                                                $si++;
                                                extract($records)
                                                ?>
                                                <tr>
                                                    <td><?=$si?></td>
                                                    <td><?=$username?></td>
                                                    <td><?=$role?></td>
                                                    <td><?=$last_login?></td>
                                                    <td><?=$employee->sale_count($username)?></td>
                                                    <td>
                                                        <?php if (($permission->employee_view())){ ?>
                                                        <a style="cursor: pointer" class="label label-info" href="#" onclick="view_employee(<?=$id?>)"><i class="fa fa-eye"></i></a>
                                                        <?php } ?>
                                                        <?php if ($permission->employee_update()){ ?>
                                                        <a style="cursor: pointer" class="label label-success" href="#" onclick="load_employee(<?=$id?>)"><i class="fa fa-edit"></i></a>
                                                       <?php } ?>
                                                        <?php if ($permission->employee_delete()){ ?>
                                                        <a style="cursor: pointer" class="label label-danger" href="#" onclick="remove_employee('<?=$id?>')"><i class="fa fa-trash"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                </div>





                                <!--modal call --->

                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="employeeModal" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title"><strong>Add New Employee</strong></h4>
                                            </div>
                                            <div class="modal-body">

                                                <form role="form" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="username"><strong>Username</strong></label>
                                                        <input type="text" name="username" autocomplete="off" class="form-control" id="username" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="password"><strong>Password</strong></label>
                                                        <input type="password" autocomplete="off" class="form-control" name="password" id="password" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="role"><strong>Role</strong></label>
                                                        <select class="form-control" id="role">
                                                           <option value="admin">Admin</option>
                                                           <option value="cashier">Cashier</option>
                                                        </select>
                                                    </div>


                                                    <input type="hidden" id="token" value="<?=Token::generate()?>">


                                                    <button type="button" onclick="add_employee()" id="product_new" class="btn btn-success" style="border-radius: 0">Add Employee</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--ends -->

                                <!--update employee -->
                                <button data-toggle="modal" href="#employeeEdit" id="load_update" style="display: none"></button>
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="employeeEdit" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title"><strong>Update Employee</strong></h4>
                                            </div>
                                            <div class="modal-body">

                                                <form role="form" enctype="multipart/form-data">

                                                    <div class="form-group">
                                                        <label for="username"><strong>ID</strong></label>
                                                        <input type="text" readonly name="update_id" autocomplete="off" class="form-control" id="update_id" placeholder="">
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="username"><strong>Username</strong></label>
                                                        <input type="text" name="update_username" autocomplete="off" class="form-control" id="update_username" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="update_password"><strong>Password</strong></label>
                                                        <input type="password" autocomplete="off" class="form-control" name="update_password" id="update_password" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="role"><strong>Role</strong></label>
                                                        <select class="form-control" id="update_role">
                                                            <option value="admin">Admin</option>
                                                            <option value="cashier">Cashier</option>
                                                        </select>
                                                    </div>

                                                    <input type="hidden" id="token" value="<?=Token::generate()?>">


                                                    <button type="button" onclick="update_employee()" id="product_new" class="btn btn-success" style="border-radius: 0">Update Employee</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--ends here --->

                                <!--view employee begins here --->
                                <button data-toggle="modal" href="#myModalDetails" id="load_employee_view" style="display: none"></button>
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalDetails" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Employee Info</h4>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-responsive">
                                                    <tbody>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <td id="get_employee_name"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Employee Password</th>
                                                        <td id="get_employee_password"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Employee Role</th>
                                                        <td id="get_employee_role"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Login</th>
                                                        <td id="get_employee_last_login"></td>
                                                    </tr>

                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!---ends here --->







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

