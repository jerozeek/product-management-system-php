<?php

class Permissions
{
    private $conn;
    public $user_id;
    public $role;
    public $sales,
           $employee,
           $product,
           $customer,
           $report,
           $expired,
           $employee_id,
           $expenses;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function role_check(){
        $query = 'select role from users where id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->role = $row['role'];
        if ($this->role == 'admin'){
            return true;
        }else{
            return false;
        }
    }
    public function read(){
        $query = "SELECT * from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $this->product = $product;
            $this->customer = $customer;
            $this->sales = $sales;
            $this->employee = $employee;
            $this->report = $report;
            $this->expired = $expired;
            $this->expenses = $expenses;
            $this->employee_id = $employee_id;
        }
    }
    //sales update
    public function update_sale(){
        $query = "update permissions set sales = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->sales, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    //update product
    public function update_product(){
        $query = "update permissions set product = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->product, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    //update customer
    public function update_customer(){
        $query = "update permissions set customer = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->customer, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    //employee customer
    public function update_employee(){
        $query = "update permissions set employee = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->employee, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    //expiry customer
    public function update_expired(){
        $query = "update permissions set expired = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->expired, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    //report customer
    public function update_report(){
        $query = "update permissions set report = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->report, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    #update expenses
    public function update_expenses(){
        $query = "update permissions set expenses = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->expenses, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }




    public function update(){
        $query = "UPDATE permission set product = ?, customer = ?, sales = ?, employee = ?, report = ?, expired = ? where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->product, PDO::PARAM_STR);
        $stmt->bindParam(2, $this->customer, PDO::PARAM_STR);
        $stmt->bindParam(3, $this->sales, PDO::PARAM_STR);
        $stmt->bindParam(4, $this->employee, PDO::PARAM_STR);
        $stmt->bindParam(5, $this->report, PDO::PARAM_STR);
        $stmt->bindParam(6, $this->expired, PDO::PARAM_STR);
        $stmt->bindParam(7, $this->employee_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    //Sales permission page begins here.....
    private function sales(){
        $query = "SELECT sales from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $d = $row['sales'];
        }
        $sales = json_decode($d);
        return $sales;
    }
    public function sales_menu(){
        $menu = self::sales();
        foreach ($menu as $item){
            if ($item->sales == 1){
                return true;
            }
        }
    }
    public function sales_add(){
        $add_btn = self::sales();
        foreach ($add_btn as $item){
            if ($item->add == 1){
                return true;
            }
        }
    }
    public function sales_view(){
        $view_btn = self::sales();
        foreach ($view_btn as $item){
            if ($item->view == 1){
                return true;
            }
        }
    }
    public function sales_update(){
        $update_btn = self::sales();
        foreach ($update_btn as $item){
            if ($item->edit == 1){
                return true;
            }
        }
    }
   public function check_sale_update_status(){
        $code = 55;
        $stmt = $this->conn->prepare('SELECT return_sales from config where code = ?');
        $stmt->bindParam(1,$code,PDO::PARAM_INT);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if ($row['return_sales'] == 1){
                return true;
            }else{
                return false;
            }
        }
   }
    public function sales_delete(){
        $delete_btn = self::sales();
        foreach ($delete_btn as $item){
            if ($item->delete == 1){
                return true;
            }
        }
    }
    //Sales permissions implementation ends here
    //Expenses permission page begins here.....
    private function Expenses(){
        $query = "SELECT expenses from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $d = $row['expenses'];
        }
        return json_decode($d);
    }
    public function expenses_menu(){
        $menu = self::Expenses();
        foreach ($menu as $item){
            if ($item->expenses == 1){
                return true;
            }
        }
    }
    public function expenses_add(){
        $add_btn = self::Expenses();
        foreach ($add_btn as $item){
            if ($item->add == 1){
                return true;
            }
        }
        return false;
    }
    public function expenses_view(){
        $view_btn = self::Expenses();
        foreach ($view_btn as $item){
            if ($item->view == 1){
                return true;
            }
        }
    }
    public function expenses_update(){
        $update_btn = self::Expenses();
        foreach ($update_btn as $item){
            if ($item->edit == 1){
                return true;
            }
        }
    }
    public function expenses_delete(){
        $delete_btn = self::Expenses();
        foreach ($delete_btn as $item){
            if ($item->delete == 1){
                return true;
            }
        }
    }
    //expenses permissions implementation ends here
    //Product Permissions begins here.....
    private function Product(){
        $query = "SELECT product from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $d = $row['product'];
        }
        $product = json_decode($d);
        return $product;
    }
    public function product_menu(){
        $menu = self::Product();
        foreach ($menu as $item){
            if ($item->product == 1){
                return true;
            }
        }
    }
    public function product_view(){
        $view_btn = self::Product();
        foreach ($view_btn as $item){
            if ($item->view == 1){
                return true;
            }
        }
    }
    public function product_add(){
        $add_btn = self::Product();
        foreach ($add_btn as $item){
            if ($item->add == 1){
                return true;
            }
        }
    }
    public function product_update(){
        $update_btn = self::Product();
        foreach ($update_btn as $item){
            if ($item->edit == 1){
                return true;
            }
        }
    }
    public function product_delete(){
        $delete_btn = self::Product();
        foreach ($delete_btn as $item){
            if ($item->delete == 1){
                return true;
            }
        }
    }
    //Product Permission Ends here....

    //Customer Permissions begins here.....
    private function Customer(){
        $query = "SELECT customer from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $d = $row['customer'];
        }
        $customer = json_decode($d);
        return $customer;
    }
    public function customer_menu(){
        $menu = self::Customer();
        foreach ($menu as $item){
            if ($item->customer == 1){
                return true;
            }
        }
    }
    public function customer_view(){
        $view_btn = self::Customer();
        foreach ($view_btn as $item){
            if ($item->view == 1){
                return true;
            }
        }
    }
    public function customer_add(){
        $add_btn = self::Customer();
        foreach ($add_btn as $item){
            if ($item->add == 1){
                return true;
            }
        }
    }
    public function customer_update(){
        $update_btn = self::Customer();
        foreach ($update_btn as $item){
            if ($item->edit == 1){
                return true;
            }
        }
    }
    public function customer_delete(){
        $delete_btn = self::Customer();
        foreach ($delete_btn as $item){
            if ($item->delete == 1){
                return true;
            }
        }
    }
    //Customer Permission Ends here....

 //Employee Permissions begins here.....
    private function Employee(){
        $query = "SELECT employee from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $d = $row['employee'];
        }
        $employee = json_decode($d);
        return $employee;
    }
    public function employee_menu(){
        $menu = self::Employee();
        foreach ($menu as $item){
            if ($item->employee == 1){
                return true;
            }
        }
    }
    public function employee_view(){
        $view_btn = self::Employee();
        foreach ($view_btn as $item){
            if ($item->view == 1){
                return true;
            }
        }
    }
    public function employee_add(){
        $add_btn = self::Employee();
        foreach ($add_btn as $item){
            if ($item->add == 1){
                return true;
            }
        }
    }
    public function employee_update(){
        $update_btn = self::Employee();
        foreach ($update_btn as $item){
            if ($item->edit == 1){
                return true;
            }
        }
    }
    public function employee_delete(){
        $delete_btn = self::Employee();
        foreach ($delete_btn as $item){
            if ($item->delete == 1){
                return true;
            }
        }
    }
    //Employee Permission Ends here....

    //Report Permissions begins here.....
    private function Report(){
        $query = "SELECT report from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $d = $row['report'];
        }
        $report = json_decode($d);
        return $report;
    }
    public function report_menu(){
        $menu = self::Report();
        foreach ($menu as $item){
            if ($item->report == 1){
                return true;
            }
        }
    }
    public function report_sales(){
        $sale = self::Report();
        foreach ($sale as $item){
            if ($item->sales == 1){
                return true;
            }
        }
    }public function report_product(){
        $product = self::Report();
        foreach ($product as $item){
            if ($item->product == 1){
                return true;
            }
        }
    }public function report_employee(){
        $employee = self::Report();
        foreach ($employee as $item){
            if ($item->employee == 1){
                return true;
            }
        }
    }public function report_customer(){
        $customer = self::Report();
        foreach ($customer as $item){
            if ($item->customer == 1){
                return true;
            }
        }
    }
    //Report Permission Ends here....
    //Expired Permission
    //Report Permissions begins here.....
    private function Expired(){
        $query = "SELECT expired from permissions where employee_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $d = $row['expired'];
        }
        $expired = json_decode($d);
        return $expired;
    }
    public function expired_view(){
        $expired = self::Expired();
        foreach ($expired as $item){
            if ($item->expire == 1){
                return true;
            }
        }
    }






}