<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$permission = new Permissions($db);
$backup = new Backup($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'backup');
if (!$permission->role_check()){
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
                                        <span class="fa fa-database"></span>  <strong>Backup</strong>
                                    </a>
                                <?php  } ?>
                            </header>
                            <div class="panel-body">
                                <small style="margin-left: 40%" id="status"></small>
                                <?php  if (Session::exist('success')){
                                    echo Session::flash('success');
                                }
                                elseif (Session::exist('error')){
                                    echo Session::flash('error');
                                } ?>
                                <form action="../do_backup.php">
                                    <input type="hidden" value="<?=Token::generate()?>" name="do_backup">
                                <button style="margin-bottom: 30px; border-radius: 0px; font-weight: bold" class="btn btn-primary"><span class="fa fa-database"></span> Create Backup</button>
                                </form>
                                <div class="col-md-12">
                                    <table class="table table-responsive" id="datatable">
                                        <thead>
                                        <tr>
                                            <th>FileName</th>
                                            <th>File Size</th>
                                            <th>Created</th>
                                            <th>Download</th>
                                            <th>Delete</th>
                                            <th>Upload</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $files = scandir('../../backups',1);
                                        asort($files);
                                        $sl = 0;
                                        foreach ($files as $file) {
                                            $sl++;
                                            if (is_string($file) && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                                        ?>
                                            <tr>
                                                <td><?=$file?></td>
                                                <td><?php  echo $backup->formatSizeUnits(filesize('../../backups/'.$file)) . '<br>'; ?></td>
                                                <td><?php echo date ("d M Y H:i:s",filemtime('../../backups/'.$file)); ?></td>
                                                <td>
                                                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 0px"><span class="fa fa-download"></span> <a style="text-decoration: none; color: white" href="../download_backup.php?filename=<?=$file?>">Download</a></button>
                                                </td>
                                                <td><button class="btn btn-sm btn-danger" style="border-radius: 0px"><span class="fa fa-trash"></span> <a style="text-decoration: none; color: white" href="../download_backup.php?delete_file=<?=$file?>">Delete</a></button></td>
                                                <td><button class="btn btn-sm btn-info" style="border-radius: 0px"><span class="fa fa-cloud-upload"></span> <a style="text-decoration: none; color: white" href="../api/resource.php?filename=<?=$file?>"> Upload to Live Server</button></td>
                                                 </tr>
                                        <?php   } } ?>
                                        </tbody>
                                    </table>


                                </div>

                            </div>
                        </section>
                    </div>
                </div>
                <a href="#customer_info" data-toggle="modal" class="btn btn-warning" id="display_customer_info" style="display: none;"></a>
                <a href="#update_customer_info" data-toggle="modal" class="btn btn-danger" id="update_customer" style="display: none"></a>


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
<script>
    function download_backup(filename) {
        $.ajax({
            url:'../do_backup.php',
            method:'post',
            dataType:'text',
            data:{backup_file:filename},
            beforeSend:function(){
              $('#status').text('Your Download will begin shortly');
            },
            success:function (response) {

            }
        })
    }
</script>

</body>
</html>

