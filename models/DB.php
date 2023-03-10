<?php


class DB
{
    private $conn;
    private $host = 'localhost';
    private $db_name = 'pems';
    private $username = 'root';
    private $password = 'root';

    public function connect()
    {
        $this->conn = null;
        try{
            $this->conn = new PDO('mysql:host='.$this->host. ';dbname='.$this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e){
            return $e->getMessage();
        }
        return $this->conn;
    }


}