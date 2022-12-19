<?php


class Settings
{

    private $conn;
    public $store_name;
    public $image;
    public $code = 55;
    public $address;
    public $return_status;
    public $sale_expired;
    public $store_key;

    function __construct($db)
    {
        $this->conn = $db;
    }
    public function read(){
        $query = "SELECT * FROM config where code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->code,PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $this->store_name = $store_name;
            $this->image = $logo;
            $this->address = $address;
            $this->return_status = $return_sales;
            $this->sale_expired = $sale_expired;
            $this->store_key = $store_key;
        }
    }
    public function update_logo(){
        $query = "UPDATE config set logo = ? where code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->image, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->code, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return true;
        }
    }
    public function update(){
        $query = "UPDATE config set store_name = ?, address=?, return_sales=?, sale_expired = ?, store_key = ? where code= ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->store_name, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->address, PDO::PARAM_STR);
        $stmt->bindParam(3,$this->return_status, PDO::PARAM_INT);
        $stmt->bindParam(4,$this->sale_expired, PDO::PARAM_INT);
        $stmt->bindParam(5,$this->store_key, PDO::PARAM_INT);
        $stmt->bindParam(6,$this->code, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
   public function sales_expired(){
        $code = 55;
        $query = "select sale_expired from config where code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$code,PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row['sale_expired'];
        }
   }



}