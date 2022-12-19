<?php


class Products
{
    private $conn;
    //set properties
    public $product;
    public $quantity;
    public $c_price;
    public $s_price;
    public $manufacturer;
    public $expiry;
    public $product_id;
    public $product_status;
    public $category;
    public $count;
    public $expired;
    public $date;

    public function __construct($db)
    {
        date_default_timezone_set('Africa/Lagos');
        $this->conn = $db;
        $this->date = date('Y-m-d');
    }
    public function get_all(){
        $query = "SELECT * FROM product ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function get_by_expired(){
        $query = "SELECT * FROM product where quantity > 0 ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function create(){
        $query = "INSERT INTO product (name,category,quantity,cost_price,selling_price,manufacturer,created,expiry) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->product,PDO::PARAM_STR);
        $stmt->bindParam(2,$this->category,PDO::PARAM_STR);
        $stmt->bindParam(3,$this->quantity,PDO::PARAM_STR);
        $stmt->bindParam(4,$this->c_price,PDO::PARAM_STR);
        $stmt->bindParam(5,$this->s_price,PDO::PARAM_STR);
        $stmt->bindParam(6,$this->manufacturer,PDO::PARAM_STR);
        $stmt->bindParam(7,$this->date,PDO::PARAM_STR);
        $stmt->bindParam(8,$this->expiry,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function delete(){
        $query = "delete from product where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->product_id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function view(){
        $query = 'select * from product where id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->product_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->product_id = $row['id'];
        $this->product = $row['name'];
        $this->category = $row['category'];
        $this->quantity = $row['quantity'];
        $this->c_price = $row['cost_price'];
        $this->s_price = $row['selling_price'];
        $this->expiry = $row['expiry'];
        $this->manufacturer = $row['manufacturer'];
    }
    public function update(){
        $query = "update product set name = ?, category=?,quantity = ?, cost_price = ?, selling_price = ?, manufacturer = ?, created = ?, expiry = ? where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->product, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->category, PDO::PARAM_STR);
        $stmt->bindParam(3,$this->quantity, PDO::PARAM_INT);
        $stmt->bindParam(4,$this->c_price, PDO::PARAM_INT);
        $stmt->bindParam(5,$this->s_price, PDO::PARAM_INT);
        $stmt->bindParam(6,$this->manufacturer, PDO::PARAM_STR);
        $stmt->bindParam(7,$this->date,PDO::PARAM_STR);
        $stmt->bindParam(8,$this->expiry,PDO::PARAM_STR);
        $stmt->bindParam(9,$this->product_id,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function check_product(){
        $query = "SELECT * from product where name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->product, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function add_category(){
        $query = "INSERT INTO category (name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->category,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function check_category(){
        $query = "SELECT * from category where name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->category, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function get_category(){
        $query = "SELECT * from category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $data = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = $row;
        }
        return $data;
    }
    public function count(){
        $query = "SELECT * FROM product ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $this->count = $stmt->rowCount();
    }

    public function expired_product($expiry_date){
        $today = date('Y-m-d');
        $x_day = "$expiry_date";
        $d1 = new DateTime($today);
        $d2 = new DateTime($x_day);
        $diff = $d1->diff($d2);
        $this->expired = $diff->days;
    }
    public function expired_count(){
        $query = "select expiry from product where CURRENT_DATE > expiry";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return $stmt->rowCount();
        }else{
            return 0;
        }
    }
    public function item_details(){
        $query = "SELECT * FROM product where name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->product, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;

    }
}