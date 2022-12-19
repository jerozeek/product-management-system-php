<?php
header('Content-Type:application/json');
require_once '../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$permission = new Permissions($db);
if (isset($_POST['employee_id'])){
    //load the current permission
    $permission->user_id = $_POST['employee_id'];
    $permission->read();
    //get properties;
    $sales = json_decode($permission->sales,true);
    $product = json_decode($permission->product, true);
    $employees = json_decode($permission->employee, true);
    $customer = json_decode($permission->customer, true);
    $expense = json_decode($permission->expenses, true);
    $report = json_decode($permission->report, true);
        //run for the sale
        //process only the sales permission and hold the result to insert later....
        if (isset($_POST['sales'])){
            $sale = "1";
        }else{
            $sale = "0";
        }
        if (isset($_POST['sales_add'])){
            $sale_add = "1";
        }else{
            $sale_add = "0";
        }
        if (isset($_POST['sales_view'])){
            $sale_view = "1";
        }else{
            $sale_view = "0";
        }
        if (isset($_POST['sales_edit'])){
            $sale_edit = "1";
        }else{
            $sale_edit = "0";
        }
        if (isset($_POST['sales_delete'])){
            $sale_delete = "1";
        }else{
            $sale_delete = "0";
        }
        $sale_permission[] = array(
            'sales' => "$sale",
            'add' => "$sale_add",
            'view' => "$sale_view",
            'edit' => "$sale_edit",
            'delete' => "$sale_delete"
        );
        //sales permission update...
        $permission->sales = json_encode($sale_permission);
        //store the result inside the sales_complete....
        $permission->update_sale();
        //process the product permission
        if (isset($_POST['product'])){
            $product = "1";
        }else{
            $product = "0";
        }
        if (isset($_POST['product_add'])){
            $product_add = "1";
        }else{
            $product_add = "0";
        }
        if (isset($_POST['product_view'])){
            $product_view = "1";
        }else{
            $product_view = "0";
        }
        if (isset($_POST['product_edit'])){
            $product_edit = "1";
        }else{
            $product_edit = "0";
        }
        if (isset($_POST['product_delete'])){
            $product_delete = "1";
        }else{
            $product_delete = "0";
        }
        $product_permission[] = array(
        'product' => "$product",
        'add' => "$product_add",
        'view' => "$product_view",
        'edit' => "$product_edit",
        'delete' => "$product_delete"
    );
    //product permission update...
    $permission->product = json_encode($product_permission);
    //store the result inside the sales_complete....
    $permission->update_product();

    //process customer permission update
    //process the product permission
    if (isset($_POST['customer'])){
        $customer = "1";
    }else{
        $customer = "0";
    }
    if (isset($_POST['customer_add'])){
        $customer_add = "1";
    }else{
        $customer_add = "0";
    }
    if (isset($_POST['customer_view'])){
        $customer_view = "1";
    }else{
        $customer_view = "0";
    }
    if (isset($_POST['customer_edit'])){
        $customer_edit = "1";
    }else{
        $customer_edit = "0";
    }
    if (isset($_POST['customer_delete'])){
        $customer_delete = "1";
    }else{
        $customer_delete = "0";
    }
    $customer_permission[] = array(
        'customer' => "$customer",
        'add' => "$customer_add",
        'view' => "$customer_view",
        'edit' => "$customer_edit",
        'delete' => "$customer_delete"
    );
    $permission->customer = json_encode($customer_permission);
    $permission->update_customer();

    //process employee permissions...
    if (isset($_POST['employee'])){
        $employee = "1";
    }else{
        $employee = "0";
    }
    if (isset($_POST['employee_add'])){
        $employee_add = "1";
    }else{
        $employee_add = "0";
    }
    if (isset($_POST['employee_view'])){
        $employee_view = "1";
    }else{
        $employee_view = "0";
    }
    if (isset($_POST['employee_edit'])){
        $employee_edit = "1";
    }else{
        $employee_edit = "0";
    }
    if (isset($_POST['employee_delete'])){
        $employee_delete = "1";
    }else{
        $employee_delete = "0";
    }
    $employee_permission[] = array(
        'employee' => "$employee",
        'add' => "$employee_add",
        'view' => "$employee_view",
        'edit' => "$employee_edit",
        'delete' => "$employee_delete"
    );
    $permission->employee = json_encode($employee_permission);
    $permission->update_employee();


    //process Report permissions...
    if (isset($_POST['employee_report'])){
        $employee_report = "1";
    }else{
        $employee_report = "0";
    }
    if (isset($_POST['sales_report'])){
        $sales_report = "1";
    }else{
        $sales_report = "0";
    }
    if (isset($_POST['customer_report'])){
        $customer_report = "1";
    }else{
        $customer_report = "0";
    }
    if (isset($_POST['product_report'])){
        $product_report = "1";
    }else{
        $product_report = "0";
    }
    $report_permission[] = array(
        'employee' => "$employee_report",
        'product' => "$product_report",
        'sales' => "$sales_report",
        'customer' => "$customer_report",
    );
    $permission->report = json_encode($report_permission);
    $permission->update_report();


 //process Report permissions...
    if (isset($_POST['expenses'])){
        $expenses = "1";
    }else{
        $expenses = "0";
    }
    if (isset($_POST['expenses_add'])){
        $expenses_add = "1";
    }else{
        $expenses_add = "0";
    }
    if (isset($_POST['expenses_view'])){
        $expenses_view = "1";
    }else{
        $expenses_view = "0";
    }
    if (isset($_POST['expenses_edit'])){
        $expenses_edit = "1";
    }else{
        $expenses_edit = "0";
    }if (isset($_POST['expenses_delete'])){
        $expenses_delete = "1";
    }else{
        $expenses_delete = "0";
    }
    $expenses_permission[] = array(
        'expenses' => "$expenses",
        'add' => "$expenses_add",
        'view' => "$expenses_view",
        'edit' => "$expenses_edit",
        'delete' => "$expenses_delete",
    );
    $permission->expenses = json_encode($expenses_permission);
    $permission->update_expenses();
    #-----close expenses----
    //expired menu
    if (isset($_POST['expiry'])){
        $expiry = 1;
    }else{
        $expiry = 0;
    }
    $expired_permission[] = array(
        'expire' => "$expiry"
    );
    $permission->expired = json_encode($expired_permission);
    $permission->update_expired();
    $data['status'] = true;
    $data['message'] = 'Employee Role have been Successfully Updated';
    echo json_encode($data);
}
