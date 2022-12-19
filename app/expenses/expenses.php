<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$permission = new Permissions($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'expenses');
$employee = new Employee($db);
$expenses = new Expenses($db);
$expense = $expenses->read();
$result = $employee->read();
if (!$permission->expenses_menu()){
    Redirect::to('../home/home.php');
}
?>
<!DOCTYPE html>
<head>
    <title>PES-Expenses</title>
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
                                <?php if ($permission->expenses_add()){ ?>
                                    <a style="float: left; margin-top: 10px; border-radius: 0" href="#employeeModal" data-toggle="modal" class="btn btn-primary">
                                        <span class="fa fa-plus"></span>  <strong>Add New Expenses</strong>
                                    </a>
                                <?php } ?>
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="datatable">
                                        <thead>
                                        <tr>
                                            <th>SI</th>
                                            <th>Employee</th>
                                            <th>Purpose</th>
                                            <th>Amount(<?php echo Session::get(Config::get('session/currency'))?>)</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php
                                        $si = 0;
                                        while ($records = $expense->fetch(PDO::FETCH_ASSOC)){
                                            $si++;
                                            extract($records);
                                            ?>
                                            <tr>
                                                <td><?=$si?></td>
                                                <td><?=$employee?></td>
                                                <td><?=substr($purpose,0,20)?></td>
                                                <td><?=$amount?></td>
                                                <td><?=$created?></td>
                                                <td>
                                                    <?php if (($permission->expenses_view())){ ?>
                                                        <a style="cursor: pointer" class="label label-info" href="#" onclick="view_expenses(<?=$expenses_id?>)"><i class="fa fa-eye"></i></a>
                                                    <?php } ?>
                                                    <?php if ($permission->expenses_update()){ ?>
                                                        <a style="cursor: pointer" class="label label-success" href="#" onclick="load_expenses(<?=$expenses_id?>)"><i class="fa fa-edit"></i></a>
                                                    <?php } ?>
                                                    <?php if ($permission->expenses_delete()){ ?>
                                                        <a style="cursor: pointer" class="label label-danger" href="#" onclick="remove_expenses('<?=$expenses_id?>')"><i class="fa fa-trash"></i></a>
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
                                                <h4 class="modal-title"><b>Add New Expenses</b></h4>
                                            </div>
                                            <div class="modal-body">

                                                <form role="form" id="add_expenses">
                                                    <div class="form-group">
                                                        <label for="username"><b>Amount(<?=Session::get(Config::get('session/currency'))?>)</b></label>
                                                        <input type="number" name="amount" required autocomplete="off" class="form-control" id="amount" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="purpose"><b>Purpose of Expenses</b></label>
                                                        <textarea class="form-control" required autocomplete="off" id="purpose" name="purpose" placeholder="Purpose....."></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="password"><b>Assign to</b></label>
                                                       <select class="form-control" id="person" name="person" required>
                                                           <?php foreach ($result as $assigned){ ?>
                                                           <option value="<?=$assigned['username']?>"><?=$assigned['username']?></option>
                                                           <?php } ?>
                                                       </select>
                                                    </div>

                                                    <input type="hidden" id="token" value="<?=Token::generate()?>">

                                                    <button type="submit" onclick="add_expenses()" class="btn btn-success" style="border-radius: 0">Add Expenses</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--ends -->

                                <!--update employee -->
                                <button data-toggle="modal" href="#expensesEdit" id="load_expenses_update" style="display: none"></button>
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="expensesEdit" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title"><strong>Update Employee</strong></h4>
                                            </div>
                                            <div class="modal-body">

                                                <form role="form" enctype="multipart/form-data" id="update_expenses_form">

                                                    <div class="form-group">
                                                        <label for="username"><strong>Expenses Id</strong></label>
                                                        <input type="text" readonly name="expenses_id" autocomplete="off" class="form-control" id="expenses_id" placeholder="">
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="username"><strong>Amount</strong></label>
                                                        <input type="text" name="expenses_amount" autocomplete="off" class="form-control" id="expenses_amount" placeholder="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="expenses_purpose"><strong>Expenses Purpose</strong></label>
                                                        <textarea class="form-control" autocomplete="off" id="expenses_purpose" name="expenses_purpose"></textarea>
                                                    </div>



                                                    <div class="form-group">
                                                        <label for="role"><strong>Assigned To</strong></label>
                                                        <select class="form-control" id="expenses_assigned_to" name="expenses_assigned_to">
                                                            <?php foreach ($result as $assigned){ ?>
                                                                <option value="<?=$assigned['username']?>"><?=$assigned['username']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <input type="hidden" id="token" value="<?=Token::generate()?>">


                                                    <button type="submit" id="update_expenses" class="btn btn-success" style="border-radius: 0">Update Expenses</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--ends here --->

                                <!--view expensis begins here --->
                                <button data-toggle="modal" href="#myexpensesDetails" id="load_expenses_view" style="display: none"></button>
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myexpensesDetails" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Expenses Details</h4>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-responsive">
                                                    <tbody>
                                                    <tr>
                                                        <th>Purpose of Expenses</th>
                                                        <td id="get_expenses_purpose"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Amount(<?=Session::get(Config::get('session/currency'))?>)</th>
                                                        <td id="get_expenses_amount"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Assigned By</th>
                                                        <td id="get_expenses_assigned_by"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Assigned To</th>
                                                        <td id="get_expenses_assigned_to"></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date Carried Out</th>
                                                        <td id="get_expenses_date"></td>
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
    $('#datatable').DataTable( {
        responsive: true,
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [ {extend: 'copy', className: 'btn-sm'},
            {extend: 'csv', title: 'Expenses Records', className: 'btn-sm'},
            {extend: 'excel', title: 'Expenses Records', className: 'btn-sm'},
            {extend: 'pdf', title: 'Expenses Records', className: 'btn-sm'},
            {extend: 'print', className: 'btn-sm'} ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1:typeof i === 'number' ? i : 0;
            };
            //#----------- Total over this page------------------#
            expenses = api.column(3, { page: 'current'} ).data().reduce( function (a, b) {
                return intVal(a) + intVal(b);
            },0);
            //#-----------ends of Total over this page------------------#
            // Update footer
            $( api.column(3).footer()).html(expenses.toFixed(2));
        }
    });
</script>
</body>
</html>

