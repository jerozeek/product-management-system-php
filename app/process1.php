<?php
require_once '../autoload.php';
require_once 'barcode/barcode.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$product = new Products($db);
$bill = new Sales($db);
$employee = new Employee($db);
$customer = new Customers($db);
$settings = new Settings($db);
$expenses = new Expenses($db);
$sales_product = $bill->sales_item();
//generate sales bill
if (isset($_POST['sale_product'],$_POST['sale_quantity'],$_POST['sale_price'],$_POST['subtotal'],$_POST['sale_category'],$_POST['grand_total'])){
    $bill->grand_total = $_POST['grand_total'];
    $bill->employee = $bill->get_seller(Session::get(Config::get('session/user_id')));
    $bill->product = $_POST['sale_product'];
    $bill->quantity = $_POST['sale_quantity'];
    $bill->price = $_POST['sale_price'];
    $bill->profit = $_POST['profit'];
    $bill->payment_method = $_POST['payment_method'];
    $bill->customer_id = $_POST['customer_id'];
    $customer->customer_id = $bill->customer_id;
    $bill->customer_name = $customer->customer_name();
    $subtotal = $_POST['subtotal'];
    $bill->paid = $_POST['paid'];
    $bill->due = $_POST['due'];
    //generate the bill
    $bill->bill();
    //check if payment if complete....and send the fund to customers balance.....
    if ($bill->due > 0){
        //now the customer is owing the store
        $customer->balance_in = $bill->due;
        $customer->update_customer_balance();
    }
    //loop through the details
    for($i=0; $i < sizeof($bill->product); $i++){
        $detailData = array(
            'bill_id' => $bill->bill_id,
            'product_name' => $bill->product[$i],
            'product_quantity' => $bill->quantity[$i],
            'product_prices' =>$bill->price[$i],
            'product_profit' =>$bill->profit[$i] * $bill->quantity[$i],
            'product_subtotal' => $subtotal[$i]
        );
        $bill->bill_details($detailData['bill_id'],$detailData['product_name'],$detailData['product_quantity'],$detailData['product_prices'],$detailData['product_profit'],$detailData['product_subtotal']);
       //update the sold quantity.......
        $bill->update_sales_quantity($detailData['product_quantity'],$detailData['product_name']);
    }
    Session::flash('success',"<div class='alert alert-success wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Success: </strong>Bill Successfully Created</div>");
    Redirect::to("sales/sale_view.php?bill_id=$bill->bill_id");
}

//return quantity
if (isset($_GET['quantity'],$_GET['product'])){
    $bill->quantity = $_GET['quantity'];
    if ($bill->return_quantity($_GET['product'])){
       echo 'Item have been successfully returned';
    }else{
       echo 'Something went wrong';
    }
}
//return and update the bill
if (isset($_POST['return_product'],$_POST['return_quantity'],$_POST['return_price'])){
    $bill->product = $_POST['return_product'];
    $bill->quantity = $_POST['return_quantity'];
    $bill->price = $_POST['return_price'];
    $bill->profit = $_POST['profit'];
    $bill->grand_total = $_POST['grand_total'];
    $bill->paid = $_POST['paid'];
    $bill->due = $_POST['due'];
    $bill_id = $_POST['bill_id'];
    $subtotal = $_POST['subtotal'];
    //delete old details
    $bill->bill_id = $bill_id;
    if ($bill->delete_bill_details()) {
        for ($i = 0; $i < sizeof($bill->product); $i++) {
            $detailData = array(
                'bill_id' => $bill->bill_id,
                'product_name' => $bill->product[$i],
                'product_quantity' => $bill->quantity[$i],
                'product_prices' => $bill->price[$i],
                'product_profit' => $bill->profit[$i],
                'product_subtotal' => $subtotal[$i]
            );
            $bill->bill_details($detailData['bill_id'], $detailData['product_name'], $detailData['product_quantity'], $detailData['product_prices'], $detailData['product_profit'], $detailData['product_subtotal']);
        }
        //update the bill paid, due, total amount
        $bill->update_bill();
        Session::flash('success',"<div class='alert alert-success wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Success: </strong>Bill have been successfully updated</div>");
        Redirect::to("sales/sale_view.php?bill_id=$bill->bill_id");
    }
}

//delete sales
if (isset($_GET['bill_id'],$_GET['status']) && $_GET['status'] == 'delete'){
    $bill->bill_id = $_GET['bill_id'];
    if ($bill->delete_sales()){
        echo 'Sales have been Successfully Deleted';
    }else{
        echo 'Something went wrong';
    }
}

//add new employee
if (isset($_POST['employee_username'],$_POST['employee_password'],$_POST['employee_role'])){
    //set the properties
    $employee->employee = $_POST['employee_username'];
    $employee->password = md5($_POST['employee_password']);
    $employee->role = $_POST['employee_role'];
    $inserted = $employee->create();
    if ($inserted->rowCount() > 0){
        $employee->employee_permissions();
        $data['success'] = true;
        $data['employee'] = $employee->employee_id;
        $data['message'] = 'New Employee have been successfully added';
    }else{
        $data['success'] = false;
        $data['message'] = 'Something went wrong....';
    }
    echo json_encode($data);
}

//delete an Employee
if (isset($_GET['employee_id'])){
    $employee->user_id = $_GET['employee_id'];
    $remove = $employee->delete();
    if ($remove->rowCount() > 0){
        echo 'Employee record have been successfully deleted';
    }else{
        echo 'Something went wrong';
    }
}

//load employee
if (isset($_POST['employee_id'],$_POST['status'])){
    if ($_POST['status'] === 'load'){
        //load the records
        $employee->user_id = $_POST['employee_id'];
        $employee->read_by();
        $data['success'] = true;
        $data['result'] = $employee->user_id .','. $employee->employee . ',' . $employee->password . ','. $employee->role;
    }
    echo json_encode($data);
}

//update employee
if (isset($_POST['employee_update'],$_POST['id'],$_POST['username_update'],$_POST['password_update'],$_POST['role_update'])){
    $employee->employee = $_POST['username_update'];
    $employee->password = md5($_POST['password_update']);
    $employee->role = $_POST['role_update'];
    $employee->user_id = $_POST['id'];
    $updater = $employee->update();
    if ($updater->rowCount() > 0){
        $data['success'] = true;
        $data['message'] = 'Details Updated Successfully';
    }else{
        $data['success'] = true;
        $data['message'] = 'Something went wrong';
    }
    echo json_encode($data);
}

//load the employee details view
if (isset($_GET['employee'],$_GET['view_id'])){
    if ($_GET['employee'] === 'view_employee'){
        $employee->user_id = $_GET['view_id'];
        $employee->read_by();
        $data['status'] = true;
        $data['result'] = $employee->user_id .','. $employee->employee . ',' . $employee->password . ','. $employee->role . ',' . $employee->last_login;
    }
    echo json_encode($data);
}



//Add new customers

if (isset($_POST['customer_name'],$_POST['customer_email'],$_POST['customer_address'],$_POST['customer_phone'])){
    $customer->customer_name = $_POST['customer_name'];
    $customer->address = $_POST['customer_address'];
    $customer->email = $_POST['customer_email'];
    $customer->Mobile = $_POST['customer_phone'];
    $create = $customer->create();
    if ($create->rowCount() > 0){
        $data['success'] = true;
        $data['message'] = 'Customer have been successfully created';
    }
    else{
        $data['success'] = false;
        $data['message'] = 'Something went wrong';
    }
    echo json_encode($data);
}

//delete the customer
if (isset($_GET['customer_id'])){
    $customer->customer_id = $_GET['customer_id'];
    $customer->delete();
}
//load the view
if (isset($_GET['customer_report'])){
    $customer->customer_id = $_GET['customer_report'];
    $customer->read_by();
    echo $customer->customer_id . ',' . $customer->customer_name . ','.$customer->email . ','. $customer->Mobile . ',' . $customer->address . ',' . $bill->customer_balance($customer->customer_id) . ',' . $bill->customer_sales_count($customer->customer_id) . ',' . $customer->created;
}
//lload the view
if (isset($_GET['customer_display'])){
    $customer->customer_id = $_GET['customer_display'];
    $customer->read_by();
    echo $customer->customer_id . ',' . $customer->customer_name . ','.$customer->email . ','. $customer->Mobile . ',' . $customer->address . ',' . $customer->created;
}
//update the employee details
if (isset($_POST['customer_name'],$_POST['customer_email'],$_POST['customer_id'],$_POST['customer_mobile'],$_POST['customer_address'])){
    $customer->customer_id = $_POST['customer_id'];
    $customer->customer_name = $_POST['customer_name'];
    $customer->email = $_POST['customer_email'];
    $customer->address = $_POST['customer_address'];
    $customer->Mobile = $_POST['customer_mobile'];
    $update = $customer->update();
    if ($update->rowCount() > 0){
        $data['success'] = true;
        $data['result'] = 'Customer Record have been successfully updated';
    }else{
        $data['success'] = false;
        $data['result'] = 'Something went wrong';
    }
    echo json_encode($data);
}
//load item details
if (isset($_POST['selected_item'])){
    if ($_POST['status'] == 'search_item'){
        $product->product = $_POST['selected_item'];
        $details = $product->item_details();
        while ($row = $details->fetch(PDO::FETCH_ASSOC)){
            $cost = $row['cost_price'];
            $sell = $row['selling_price'];
            $profit = $sell - $cost;
            $data['success'] = true;
            $data['result'] = $row['category'] . '/' . $sell . '/' . $profit;
        }
        echo json_encode($data);
    }
}

//Application Settings
if (isset($_POST['sale_expired']) || isset($_POST['store_name']) || isset($_FILES['logo']) || isset($_POST['store_key'])){
    //upload the image first
    if ($_FILES['logo']['size'] !== 0) {
        $name = $_FILES['logo']['name'];
        $temp = explode(".",$name);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $imagepath = "../assets/products/".$newfilename;
        move_uploaded_file($_FILES["logo"]["tmp_name"],$imagepath);
        $settings->image = $newfilename;
        if (!$settings->update_logo()){
            Session::flash('error','Image was not uploaded');
            Redirect::to('settings/settings.php');
        }
    }
    //update others ....
    $settings->store_name = $_POST['store_name'];
    $settings->address = $_POST['store_address'];
    $settings->sale_expired = $_POST['sale_expired'];
    $settings->store_key = $_POST['store_key'];
    if ($_POST['return_sale'] == 'on'){
        $settings->return_status = 1;
    }else{
        $settings->return_status = 0;
    }
    if ($_POST['sale_expired'] == 'on'){
        $settings->sale_expired = 1;
    }else{
        $settings->sale_expired = 0;
    }
    $updated = $settings->update();
    Session::flash('updated','Update was successful');
    Redirect::to('settings/settings.php');
   /* if ($updated->rowCount() > 0){
        Session::flash('updated','Update was successful');
        Redirect::to('settings/settings.php');
    }else{
        Session::flash('error','Something went wrong');
        Redirect::to('settings/settings.php');
    }*/



}
if (isset($_GET['expenses_id'])){
    $id = $_GET['expenses_id'];
    $expense = $expenses->remove($id);
    if ($expense->rowCount() > 0){
        true;
    }
}
//load epenses
if (isset($_GET['expenses_view'])){
    $id = $_GET['expenses_view'];
    $expense = $expenses->load($id);
    if($expense->rowCount() > 0){
        while ($row = $expense->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $result = $purpose . '@' . $amount .'@'. $assigned_by . '@' . $employee . '@' . $created . '@' . $id;
        }
        echo $result;
    }
}
if (isset($_GET['start_date'],$_GET['end_date'],$_GET['sales_detail'])){
    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])){
        $details = array();
        $product_name = array();
        $s = explode(' ',$_GET['start_date']);
        $e = explode(' ',$_GET['end_date']);
        $start_date = str_replace('/','-',$s[0]);
        $end_date = str_replace('/','-',$e[0]);
        Session::set('start',$start_date);
        Session::set('end',$end_date);
        Redirect::to('report/sales_detail.php');
    }else if (!empty($_GET['by_employee'])){
        $employee_name = $_GET['by_employee'];
        Session::set('by_employee',$employee_name);
        Redirect::to('report/sales_detail.php');
    }else if ($_GET['by_product'] !== 'By Product'){
        $product_n = $_GET['by_product'];
        Session::set('by_product',$product_n);
        Redirect::to('report/sales_detail.php');
    }else{
        Redirect::to('report/sales_detail.php');
    }
}



