<?php


class Installation
{
    private $conn;
    public $installed;

    function __construct($db)
    {
        $this->conn = $db;
    }
    public function installation($dir){
        $query = file_get_contents($dir);
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()){
            return true;
        }
    }
    public function installation_status(){
        $table = 'config';
        if ($result = $this->conn->query("SHOW TABLES LIKE '".$table."' ")) {
            if ($result->rowCount() == 0) {
             //redirect to installation
                Redirect::to('installation.php');
            }
        }
    }
    public function check_config(){
        $table = 'config';
       if ($result = $this->conn->query("SHOW TABLES LIKE '".$table."' ")){
           if ($result->rowCount() == 0){
              return true;
           }
        }
    }
    public function installation_set(){
        $table = 'config';
        if ($result = $this->conn->query("SHOW TABLES LIKE '".$table."' ")) {
            if ($result->rowCount() == 1) {
                //redirect to installation
                Redirect::to('index.php');
            }
        }
    }

}