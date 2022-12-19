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
Session::set(Config::get('session/active_menu'),'sale');
$bill_ID = $bill->load_bill_id();
?>

            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                                <a style="float: left; margin-top: 10px; border-radius: 0" href="../sales/sale_generate.php" class="btn btn-primary">
                                    <span class="fa fa-plus"></span>  <strong>Generate New Sales</strong>
                                </a>
                        </header>
                        <div class="panel-body">
                            <div class="col-md-12">
                                <table class="table table-responsive" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>SI</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($bill)){
                                        $si = 0;
                                        while ($item = $bill_ID->fetch(PDO::FETCH_ASSOC)){
                                            $si++
                                            ?>
                                            <tr>
                                                <td><?=$si?></td>
                                                <td><?=$item['created']?></td>
                                                <td>₦<?=number_format($item['total'])?></td>
                                                <td>
                                                    <a style="cursor: pointer" class="label label-info" href="../sales/sale_view.php?bill_id=<?=$item['id']?>"><i class="fa fa-eye"></i></a>
                                                    <?php if ($permission->role_check()){ ?>
                                                     <!--   <label style="cursor: pointer" class="label label-info" onclick="sales('<?=$item['id']?>','edit')"><i class="fa fa-edit"></i></label>
                                                        <label style="cursor: pointer" class="label label-danger" onclick="sales('<?=$item['id']?>','delete')"><i class="fa fa-trash"></i></label>
                                                  -->
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


<script>
    $(document).ready(function () {
        $('#datatable').DataTable({});
    });
</script>
