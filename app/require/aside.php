<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$permission = new Permissions($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
    if (Session::get(Config::get('session/active_menu')) == 'home'){
        $active_menu = 'home';
    }if (Session::get(Config::get('session/active_menu')) == 'product'){
        $active_menu = 'product';
    }if (Session::get(Config::get('session/active_menu')) == 'sale'){
        $active_menu = 'sale';
    }if (Session::get(Config::get('session/active_menu')) == 'employee'){
        $active_menu = 'employee';
    }if (Session::get(Config::get('session/active_menu')) == 'expired'){
        $active_menu = 'expired';
    }if (Session::get(Config::get('session/active_menu')) == 'customer'){
        $active_menu = 'customer';
    }if (Session::get(Config::get('session/active_menu')) == 'permission'){
        $active_menu = 'permission';
    }if (Session::get(Config::get('session/active_menu')) == 'report_sale'){
        $active_menu = 'report_sale';
    }if (Session::get(Config::get('session/active_menu')) == 'expenses'){
        $active_menu = 'expenses';
    }if (Session::get(Config::get('session/active_menu')) == 'backup'){
        $active_menu = 'backup';
    }
?>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a class="<?php if($active_menu == 'home'){ echo 'active';} ?>" href="../home/home.php">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>


                <?php if ($permission->product_menu()){ ?>
                <li >
                    <a class="<?php if($active_menu == 'product'){ echo 'active';} ?>" href="../product/add_product.php">
                        <i class="fa fa-shopping-bag"></i>
                        <span>Inventory</span>
                    </a>
                </li>
                <?php } ?>


                <?php if ($permission->customer_menu()){ ?>
                <li>
                    <a class="<?php if($active_menu == 'customer'){ echo 'active';} ?>" href="../customers/customers.php">
                        <i class="fa fa-user"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <?php  } ?>
                <!--execute permissions-->
                <?php if ($permission->sales_menu()){ ?>
                <li class="sub-menu">
                    <a class="<?php if($active_menu == 'sale'){ echo 'active';} ?>" href="javascript:;">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Sales</span>
                    </a>
                    <ul class="sub">
                        <?php if ($permission->sales_add()){ ?>
                        <li><a href="../sales/sale_generate.php">Generate Sales</a></li>
                        <?php } ?>
                        <li><a href="../sales/sale_list.php">View Sales</a></li>
                    </ul>
                </li>
                <?php } ?>

                <?php if ($permission->employee_menu()){ ?>
                  <li>
                    <a class="<?php if ($active_menu == 'employee')echo 'active' ?>" href="../employee/employee.php">
                        <i class="fa fa-user-plus"></i>
                        <span>Employee</span>
                    </a>
                </li>
                <?php } ?>

                <?php if ($permission->role_check()){ ?>
                <li>
                    <a class="<?php if ($active_menu == 'expired')echo 'active' ?>" href="../expiry/product.php">
                        <i class="fa fa-shopping-bag"></i>
                        <span>Expired</span>
                    </a>
                </li>
                <?php } ?>

                <?php  if ($permission->expenses_menu()){ ?>
                <li>
                    <a class="<?php if($active_menu == 'expenses'){ echo 'active';} ?>" href="../expenses/expenses.php">
                        <i class="fa fa-money"></i>
                        <span>Expenses</span>
                    </a>
                </li>
                <?php  } ?>


                <?php if ($permission->role_check()){ ?>
                <li>
                    <a class="<?php if ($active_menu == 'permission')echo 'active' ?>" href="../permissions/permissions.php">
                        <i class="fa fa-lock"></i>
                        <span>Permissions</span>
                    </a>
                </li>
                <?php } ?>

                <?php if ($permission->role_check()){ ?>
                <li>
                    <a class="<?php if($active_menu == 'settings'){ echo 'active';} ?>" href="../settings/settings.php">
                        <i class="fa fa-gears"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <?php } ?>



                <?php if ($permission->role_check()){ ?>
                    <li>
                        <a class="<?php if($active_menu == 'backup'){ echo 'active';} ?>" href="../backup/backup.php">
                            <i class="fa fa-database"></i>
                            <span>Backups</span>
                        </a>
                    </li>
                <?php } ?>


                <?php if ($permission->role_check()){ ?>
                    <li class="sub-menu">
                        <a class="<?php if($active_menu == 'report'){ echo 'active';} ?>" href="javascript:;">
                            <i class="fa fa-info-circle"></i>
                            <span>Report</span>
                        </a>
                        <ul class="sub">
                            <!-- sub role of sales report -->
                            <?php  if ($permission->report_sales()){ ?>
                            <li class="sub-menu">
                                <a class="<?php if($active_menu == 'report_sale'){ echo 'active';} ?>" href="javascript:;">
                                    <i class="fa fa-shopping-bag"></i>
                                    <span>Sales</span>

                                </a>
                                <ul class="sub">
                                    <li><a href="../report/sales.php">Summary Report</a></li>
                                    <li><a href="../report/sales_detail.php">Details Report</a></li>
                                   <!-- <li><a href="../report/sales_graph.php">Graphical Report</a></li> -->
                                </ul>
                            </li>
                            <?php } ?>
                            <!--ends here --->

                            <?php  if ($permission->report_employee()){ ?>
                         <!--   <li class="sub-menu">
                                <a class="<?php if($active_menu == 'report'){ echo 'active';} ?>" href="javascript:;">
                                    <i class="fa fa-user"></i>
                                    <span>Employee</span>

                                </a>
                                <ul class="sub">
                                    <li><a href="../report/employee_summary.php">Summary Report</a></li>
                                    <li><a href="../report/employee_details.php">Details Report</a></li>
                                    <li><a href="../report/sales_graph.php">Graphical Report</a></li>
                                </ul>
                            </li>-->
                            <?php } ?>

                            <?php  if ($permission->report_product()){ ?>
                         <!--   <li class="sub-menu">
                                <a class="<?php if($active_menu == 'report'){ echo 'active';} ?>" href="javascript:;">
                                    <i class="fa fa-user"></i>
                                    <span>Product</span>

                                </a>
                                <ul class="sub">
                                    <li><a href="../report/sales.php">Summary Report</a></li>
                                    <li><a href="../report/sales_detail.php">Details Report</a></li>
                                    <li><a href="../report/sales_graph.php">Graphical Report</a></li>
                                </ul>
                            </li> -->
                            <?php } ?>

                            <?php  if ($permission->report_customer()){ ?>
                         <!--   <li class="sub-menu">
                                <a class="<?php if($active_menu == 'report'){ echo 'active';} ?>" href="javascript:;">
                                    <i class="fa fa-users"></i>
                                    <span>Customers</span>

                                </a>
                                <ul class="sub">
                                    <li><a href="../report/sales.php">Summary Report</a></li>
                                    <li><a href="../report/sales_detail.php">Details Report</a></li>
                                    <li><a href="../report/sales_graph.php">Graphical Report</a></li>
                                </ul>
                            </li> -->
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>


            </ul>
        </div>
        <!-- sidebar menu end-->
    </div>

