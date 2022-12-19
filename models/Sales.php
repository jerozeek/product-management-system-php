<?php


class Sales
{
    protected $conn;
    public $total;
    public $profit;
    public $employee;
    public $bill_id;
    public $grand_total;
    public $product;
    public $quantity;
    public $price;
    public $count;
    public $account;
    public $payment_method;
    public $amount;
    public $purpose;
    public $person;
    public $assigned_by;
    public $customer_id;
    public $customer_name;
    public $paid;
    public $due;
    public $date;
    public function __construct($db)
    {
        $this->conn = $db;
        $this->date = date('Y-m-d');
    }
    public function generate_sales($product,$quantity,$price,$interest){
        $this->bill();
        if ($this->bill_details($this->bill_id,$product,$quantity,$price,$interest)){
            return true;
        }
        return false;
    }
    public function get_seller($user_id){
        $query = "select * from users where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$user_id,PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                return $row['username'];
            }
        }
    }
    public function delete_sales(){
        $query = "delete from bill where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->bill_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $query2 = "delete from bill_details where bill_id=?";
            $stmt1 = $this->conn->prepare($query2);
            $stmt1->bindParam(1,$this->bill_id, PDO::PARAM_INT);
            $stmt1->execute();
            if ($stmt1->rowCount() > 0){
                return true;
            }
        }
    }

    public function bill(){
        $query = "INSERT INTO bill (customer_id,customer_name,paid,due,total,payment_method,created,employee) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->customer_id, PDO::PARAM_INT);
        $stmt->bindParam(2,$this->customer_name, PDO::PARAM_STR);
        $stmt->bindParam(3,$this->paid, PDO::PARAM_INT);
        $stmt->bindParam(4,$this->due, PDO::PARAM_INT);
        $stmt->bindParam(5,$this->grand_total,PDO::PARAM_INT);
        $stmt->bindParam(6,$this->payment_method,PDO::PARAM_INT);
        $stmt->bindParam(7,$this->date,PDO::PARAM_STR);
        $stmt->bindParam(8,$this->employee,PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            $this->bill_id = $this->conn->lastInsertId();
        }
    }
    public function delete_bill_details(){
        $query = "delete from bill_details where bill_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->bill_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return true;
        }
    }
    public function update_bill(){
        $query = "UPDATE bill set paid= ?, due = ?, total = ? where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->paid, PDO::PARAM_INT);
        $stmt->bindParam(2, $this->due, PDO::PARAM_INT);
        $stmt->bindParam(3, $this->grand_total, PDO::PARAM_INT);
        $stmt->bindParam(4, $this->bill_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function return_quantity($product){
        $query = "UPDATE product set quantity = quantity + ? where name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->quantity, PDO::PARAM_INT);
        $stmt->bindParam(2, $product, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return true;
        }
    }
    public function customer_balance($customer_id){
        $query = "SELECT SUM(due) as balance from bill where customer_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$customer_id,PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $balance = $row['balance'];
            return $balance;
        }
    }
    public function customer_sales_count($customer_id){
        $query = "SELECT * FROM bill where customer_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$customer_id, PDO::PARAM_INT);
        $stmt->execute();
        $row_count = $stmt->rowCount();
        return $row_count;
    }
    public function total_amount($customer_id){
        $query = "SELECT SUM(paid) as balance from bill where customer_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$customer_id,PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $balance = $row['balance'];
            return $balance;
        }
    }
    public function amount_generated($product_name){
        $query = "SELECT SUM(subtotal) as total FROM bill_details where product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$product_name, PDO::PARAM_STR);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $total = $row['total'];
            return $total;
        }
    }

    public function validate_bill_id(){
        $query = "SELECT id from bill where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->bill_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return true;
        }
    }
    public function validate_bill_details(){
        $query = "SELECT * from bill where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->bill_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function bill_details($bill_id,$product,$quantity,$price,$profit,$subtotal){
        $query = "INSERT INTO bill_details (bill_id,product,quantity,price,profit,subtotal,created) VALUES (?,?,?,?,?,?,CURRENT_DATE)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$bill_id, PDO::PARAM_INT);
        $stmt->bindParam(2,$product, PDO::PARAM_STR);
        $stmt->bindParam(3,$quantity, PDO::PARAM_INT);
        $stmt->bindParam(4,$price, PDO::PARAM_INT);
        $stmt->bindParam(5,$profit, PDO::PARAM_INT);
        $stmt->bindParam(6,$subtotal, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return true;
        }
        return false;
    }
    public function load_bill_id(){
        $query = "SELECT * from bill order by id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function load_bill_details($bill_id){
        $query = "SELECT * from bill_details where bill_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$bill_id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function update_sales_quantity($quantity,$product){
        $query = "UPDATE product set quantity = quantity - ? WHERE name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$quantity, PDO::PARAM_INT);
        $stmt->bindParam(2,$product,PDO::PARAM_STR);
        $stmt->execute();
    }

    public function count(){
        $query = "SELECT * FROM bill";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $this->count = $stmt->rowCount();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $this->account += $row['total'];
        }
    }
    //Sales items
    public function sales_item(){
        $query = "SELECT DISTINCT product from bill_details";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function sales_details($item_name){
        $query = "SELECT SUM(quantity) as quantity, SUM(profit) as profit, SUM(subtotal) as subtotal, created from bill_details where product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$item_name,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function sales_details_by_date($item_name,$start_day,$end_day){
        $query = "SELECT DISTINCT product, SUM(quantity) as quantity, SUM(profit) as profit, SUM(subtotal) as subtotal, created from bill_details where product = ? AND created BETWEEN '$start_day' AND '$end_day'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$item_name,PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetchAll();
        return $row;
    }
    public function product_by_date($start_day,$end_day){
        $query = "SELECT DISTINCT product from bill_details where created BETWEEN '$start_day' AND '$end_day' ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetchAll();
        return $row;
    }
    public function sales_details_by_employee($employee_name){
        $query = "select * from bill where employee = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$employee_name,PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetchAll();
        return $row;
    }
    public function product_by_employee($bill_id){
        $query = "SELECT DISTINCT product, SUM(quantity) as quantity, SUM(profit) as profit, SUM(subtotal) as subtotal, created from bill_details where bill_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$bill_id,PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetchAll();
        return $row;
    }

    public function sales_details_by_product($item){
        $query = "SELECT DISTINCT product, SUM(quantity) as quantity, SUM(profit) as profit, SUM(subtotal) as subtotal, created from bill_details where product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$item,PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetchAll();
        return $row;
    }






}