<?php


class Customers
{
    //set properties
    private $conn;
    public $customer_id;
    public $customer_name;
    public $address;
    public $email;
    public $Mobile;
    public $balance_in;
    public $balance_out;
    public $created;
    public $sale_count = 0;
    public $date;

    public function __construct($db)
    {
        date_default_timezone_set('Africa/Lagos');
        $this->conn = $db;
        $this->date = date('Y-m-d');
    }

    public function read(){
        $query = "SELECT * FROM customer ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_by(){
        $query = "SELECT * FROM customer WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $this->customer_id = $id;
            $this->customer_name = $name;
            $this->address = $address;
            $this->Mobile = $mobile;
            $this->email = $email;
            $this->balance_in = $balance_in;
            $this->balance_out = $balance_out;
            $this->created = $created;
        }


    }
    public function customer_name(){
        $query = "SELECT * FROM customer where id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $this->customer_name = $name;
            return $this->customer_name;
        }
    }
    public function update_customer_balance(){
        $query = "UPDATE customer set balance_in = balance_in + ? where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->balance_in, PDO::PARAM_INT);
        $stmt->bindParam(2,$this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
    }



    public function create(){
        $query = "INSERT INTO customer (name,address,mobile,email,created) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->customer_name, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->address, PDO::PARAM_STR);
        $stmt->bindParam(3,$this->Mobile, PDO::PARAM_STR);
        $stmt->bindParam(4,$this->email, PDO::PARAM_STR);
        $stmt->bindParam(5,$this->date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }

    public function update(){
        $query = "UPDATE customer set name=?,email=?,address=?,mobile=? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->customer_name, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->email, PDO::PARAM_STR);
        $stmt->bindParam(3,$this->address, PDO::PARAM_STR);
        $stmt->bindParam(4,$this->Mobile, PDO::PARAM_STR);
        $stmt->bindParam(5,$this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function delete(){
        $query = "DELETE from customer where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->customer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

}