<?php
require 'autoload.php';
header('Content-Type:json');
$database = new DB();
$db = $database->connect();
$installation = new Installation($db);
//Run the installation file
        $data = array(
            'success'=>false,
            'result'=>0
        );
        if ($installation->check_config()){
            if ($installation->installation('db/database.sql')){
                $data['success'] = true;
                $data['result'] = 'installed';
            }else{
                $data['success'] = false;
                $data['result'] = 'Please contact your service providers for help.';
            }
        }
        echo json_encode($data);




