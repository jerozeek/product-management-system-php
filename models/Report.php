<?php


 class Report
 {
     protected $conn;

     function __construct($db)
     {
         $this->conn = $db;
     }

     public function total_expenses($date)
     {
         $query = "SELECT SUM(amount) as total from expenses where created = ?";
         $stmt = $this->conn->prepare($query);
         $stmt->bindParam(1, $date, PDO::PARAM_INT);
         $stmt->execute();
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             return $row['total'];
         }
     }

     public function total_sales($date)
     {
         $query = "SELECT SUM(total) as sales from bill where created = ?";
         $stmt = $this->conn->prepare($query);
         $stmt->bindParam(1, $date, PDO::PARAM_INT);
         $stmt->execute();
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             return $row['sales'];
         }
     }

     public function total_profit($date)
     {
         $query = "SELECT SUM(profit) as profit from bill_details where created = ?";
         $stmt = $this->conn->prepare($query);
         $stmt->bindParam(1, $date, PDO::PARAM_STR);
         $stmt->execute();
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             return $row['profit'];
         }
     }

     public function years()
     {
         $date_future = date("Y", strtotime('-10 year'));
         $date_year = date("Y");
         for ($i = $date_year; $i < $date_future; $i++) {

         }
     }
 }