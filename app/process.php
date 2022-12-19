<?php
header('Content-Type:application/json');
require_once '../autoload.php';
require_once 'barcode/barcode.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$product = new Products($db);
$bill = new Sales($db);
$expenses = new Expenses($db);
$employee = new Employee($db);
$installation = new Installation($db);
//add product request
if (isset($_POST['product'],$_POST['quan'], $_POST['category_name'],$_POST['selling_price'],$_POST['cost_price'],$_POST['manufacturer'],$_POST['expiry'])) {
    if ($_POST['cost_price'] > $_POST['selling_price']){
        //validate the price
        $data['status'] = false;
        $data['result'] = 'Cost Price cannot be greater than selling price.';
    }elseif (!is_numeric($_POST['quan'])){
        //validate the price
        $data['status'] = false;
        $data['result'] = 'Quantity must be an Integer value.';
    }else{
        //insert new product
        $explode = explode(' ',$_POST['expiry']);
        $product->product = $_POST['product'];
        $product->category = $_POST['category_name'];
        $product->quantity = $_POST['quan'];
        $product->c_price = $_POST['cost_price'];
        $product->s_price = $_POST['selling_price'];
        $product->manufacturer = $_POST['manufacturer'];
        $product->expiry = $explode[0];
        //check for already existing product BY name
        $item = $product->check_product();
        if ($item->rowCount() > 0){
            //product already exist
            $data['status'] = false;
            $data['result'] = 'Product name already exist.';
        }else {
            $create = $product->create();
            if ($create->rowCount() > 0) {
                //save barcode
                $d = 'assets/barcode/'.$product->product.'/';
                if (!is_dir($d)){
                    mkdir($d);
                    $f = $product->product.'.png';
                    fopen($f,'w');
                    $filepath = $d.$f;
                    barcode($filepath, $product->product);
                    unlink($product->product.'.png');
                }
                $data['status'] = true;
                $data['result'] = 'Product have been successfully added.';
            } else {
                $data['status'] = false;
                $data['result'] = 'Something went wrong.';
            }
        }
    }
    echo json_encode($data);
}
//Add new category
if (isset($_POST['category'],$_POST['category_add'])){
    $product->category = $_POST['category'];
    $category = $product->check_category();
    $row = $category->rowCount();
    if($row > 0){
        $data['status'] = false;
        $data['message'] = 'Category name already exist';
    }else{
        $results = $product->add_category();
        if ($results->rowCount() > 0){
            $data['status'] = true;
            $data['message'] = 'Category have been successfully added.';
        }else{
            $data['status'] = false;
            $data['message'] = 'Something went wrong';
        }
    }
    echo json_encode($data);
}
//product view,update,delete
if (isset($_POST['product_id'],$_POST['status'])){
    if ($_POST['status'] == 'delete') {
        //delete the product
        $product->product_id = $_POST['product_id'];
        $remove = $product->delete();
        $data['status'] = true;
        $data['result'] = 'Product deleted successfully.';
        $data['location'] = 'delete';
    }elseif ($_POST['status'] == 'view'){
        //view the product
        $product->product_id = $_POST['product_id'];
        $product->view();
        if ($product->quantity < 1) {
            $product->product_status = "<label class='label label-danger'>out of stock</label>";
        }else{
            $product->product_status = "<label class='label label-success'>in stock</label>";
        }
        $result = $product->product_id.','.$product->product.','.$product->quantity .','.$product->c_price . ','.$product->s_price.','.$product->manufacturer . ','.$product->expiry . ',' . $product->product_status. ',' . $product->category . ','. "<img src='../assets/barcode/$product->product/$product->product.png' alt='$product->product'>";
        $data['status'] = true;
        $data['result'] = $result;
        $data['location'] = 'view';
    }elseif ($_POST['status'] == 'edit'){
        //get the data to update
        $product->product_id = $_POST['product_id'];
        $product->view();
        $result = $product->product_id .','.$product->product.','.$product->quantity .','.$product->c_price . ','.$product->s_price.','.$product->manufacturer . ','.$product->expiry .','. $product->category;
        $data['status'] = true;
        $data['result'] = $result;
        $data['location'] = 'edit';
    }
    echo json_encode($data);
}

if (isset($_POST['update_id'],$_POST['name_update'],$_POST['category_update'],$_POST['quantity_update'],$_POST['cost_update'],$_POST['sell_update'],$_POST['manufacturer_update'],$_POST['expiry_update'])){
    if (empty($_POST['quantity_update'])){
        $data['success'] = false;
        $data['message'] = 'Quantity Price cannot be empty.';
    }
    if (empty($_POST['sell_update'])){
        $data['success'] = false;
        $data['message'] = 'Selling Price cannot be empty.';
    }
    if (empty($_POST['cost_update'])){
        $data['success'] = false;
        $data['message'] = 'Cost Price cannot be empty.';
    }else{
        $ep = explode(' ',$_POST['expiry_update']);
        $product->product_id = $_POST['update_id'];
        $product->category = $_POST['category_update'];
        $product->product = $_POST['name_update'];
        $product->quantity = $_POST['quantity_update'];
        $product->c_price = $_POST['cost_update'];
        $product->s_price = $_POST['sell_update'];
        $product->manufacturer = $_POST['manufacturer_update'];
        $product->expiry = $ep[0];
        $update = $product->update();
        if ($update->rowCount() > 0) {
            $data['success'] = true;
            $data['message'] = 'Product Info Updated Successfully.';
        }else{
            $data['success'] = false;
            $data['message'] = 'Something went wrong. Please try again later.';
        }
    }
    echo json_encode($data);
}
//Expenses controller
if (isset($_POST['amount'],$_POST['purpose'],$_POST['person'])){
    $expenses->assigned_by = $employee->employee_name(Session::get(Config::get('session/user_id')));
    $expenses->person = $_POST['person'];
    $expenses->amount = $_POST['amount'];
    $expenses->purpose = $_POST['purpose'];
    $create = $expenses->create();
    if ($create->rowCount() > 0){
        $data_obj['status'] = true;
        $data_obj['result'] = 'Expenses have been generated successfully';
    }else{
        $data_obj['status'] = false;
        $data_obj['result'] = 'Something went wrong';
    }
    echo json_encode($data_obj);
}


?>
