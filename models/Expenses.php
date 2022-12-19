<?php


class Expenses extends Sales
{
    function __construct($db)
    {
        parent::__construct($db);
    }

    public function create(){
        $query = "insert into expenses (employee,purpose,amount,assigned_by,created) VALUES (?,?,?,?,CURRENT_DATE )";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->person, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->purpose, PDO::PARAM_STR);
        $stmt->bindParam(3,$this->amount, PDO::PARAM_INT);
        $stmt->bindParam(4,$this->assigned_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function read(){
        $query = "SELECT * FROM expenses ORDER BY expenses_id DESC ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function remove($id){
        $query = "delete from expenses where expenses_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function load($id){
        $query = "SELECT * FROM expenses where expenses_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}