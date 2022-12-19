<?php

class Login
{
    //set properties......
    private $conn;
    public $username;
    public $password;
    public $role;
    public $user_id;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function roles(){
        $query = 'select role from users where username = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->role = $row['role'];
        Session::set(Config::get('session/role'),$this->role);
    }
    public function login(){
        $query = 'select * from users where username = ? and password = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->username,PDO::PARAM_STR);
        $stmt->bindParam(2,$this->password,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }
    public function update_last_login(){
        $query = 'update users set last_login = ? where username = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,date('Y-m-d'),PDO::PARAM_STR);
        $stmt->bindParam(2,$this->username,PDO::PARAM_STR);
        $stmt->execute();
    }
    public function create_cashier(){
        $query = "INSERT into users (username,password,role) VALUES (?,?,?)";
    }
    public function logout(){
        Session::clear();
        Redirect::to('../index.php');
    }
    public function check_login($user_id){
        if (Session::exist(Config::get('session/user_id'))){
            if (Session::get(Config::get('session/user_id')) == $user_id){
                //access granted
            }else{
                //access deny
                Session::clear();
                Redirect::to('../../index.php');
            }
        }else{
            //access deny
            Session::clear();
            Redirect::to('../../index.php');
        }
    }
    public function Permission($role){
        if (Session::exist(Config::get('session/role'))){
            if (Session::get(Config::get('session/role')) == $role){
                //access granted
            }else{
                //access deny
                Redirect::to('errors/permission.html');
            }
        }
    }
    public function user_details($user_id){
        $query = 'select * from users where id = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$user_id,PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $data[] = $row;
        return $data;
    }



}