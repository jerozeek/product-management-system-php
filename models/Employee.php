<?php


class Employee
{
    //set some properties
    private $conn;
    public $employee, $password, $role, $user_id, $last_login, $employee_id,$date;

    public function __construct($db)
    {
        date_default_timezone_set('Africa/Lagos');
        $this->conn = $db;
        $this->date = date('Y-m-d');
    }
    public function create(){
        $query = "INSERT INTO users (username,password,role,last_login) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->employee, PDO::PARAM_STR);
        $stmt->bindParam(2,$this->password, PDO::PARAM_STR);
        $stmt->bindParam(3,$this->role, PDO::PARAM_STR);
        $stmt->bindParam(4,$this->date, PDO::PARAM_STR);
        $stmt->execute();
        $this->employee_id = $this->conn->lastInsertId();
        return $stmt;
    }
    public function employee_permissions(){
        $query = "INSERT INTO permissions (employee_id) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->employee_id,PDO::PARAM_INT);
        $stmt->execute();
    }
    public function delete(){
        $query = "DELETE from users where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function update(){
        $query = 'UPDATE users set username = ?, password = ?, role = ? WHERE id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->employee,PDO::PARAM_STR);
        $stmt->bindParam(2,$this->password,PDO::PARAM_STR);
        $stmt->bindParam(3,$this->role,PDO::PARAM_STR);
        $stmt->bindParam(4,$this->user_id,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function read(){
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function read_by(){
        $query = "SELECT * FROM users where id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->user_id,PDO::PARAM_INT);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $this->user_id = $id;
            $this->employee = $username;
            $this->password = $password;
            $this->role = $role;
            $this->last_login = $last_login;
        }
    }
    public function sale_count($employee){
        $query = "SELECT * from bill where employee = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$employee,PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0){
            return $stmt->rowCount();
        }
    }
    public function employee_name($user_id){
        $stmt = $this->conn->prepare('select username from users where id = ?');
        $stmt->bindParam(1,$user_id, PDO::PARAM_INT);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row['username'];
        }
    }

}