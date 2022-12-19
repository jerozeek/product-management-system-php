<?php
require_once '../../autoload.php';
$database = new DB();
$db = $database->connect();
$login = new Login($db);
$login->check_login(Session::get(Config::get('session/user_id')));
$permission = new Permissions($db);
$backup = new Backup($db);
$permission->user_id = Session::get(Config::get('session/user_id'));
Session::delete(Config::get('session/active_menu'));
Session::set(Config::get('session/active_menu'),'backup');
if (!$permission->role_check()){
    Redirect::to('../home/home.php');
}
$settings = new Settings($db);
$settings->read();
$key = $settings->store_key;
if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $ch = curl_init();
    $response = array();
    $post = array('key' => $key, 'file' => '@' . realpath('../../backups/'.$filename));
    curl_setopt($ch, CURLOPT_URL, 'https://quicksales.com.ng/clients/resources');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    $output = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($output,TRUE);
    if ($response['status'] == 1){
        $message = $response['response'];
        Session::set('success',"<div class='alert alert-success wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Success: </strong>$message</div>");
    }else{
        $message = $response['response'];
        Session::set('success',"<div class='alert alert-danger wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Error: </strong>$message</div>");
    }
    Redirect::to('../backup/backup.php');
}
