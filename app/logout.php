<?php
require_once '../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->logout();
