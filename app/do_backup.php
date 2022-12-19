<?php
header('Content-Type:application/json');
require_once '../autoload.php';
$database = new DB();
$db = $database->connect();
$backup = new Backup($db);
if (isset($_GET['do_backup'])){
    if (Token::check($_GET['do_backup'])){
       if ($backup->do_backup()){
           Session::set('success', "<div class='alert alert-success wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Success: </strong> Backup was Successfully Created</div>");
           Redirect::to('backup/backup.php');
       }
    }
    if (isset($_POST['download_backup'])){
        $filename = $_POST['download_backup'];
        echo $filename;
        //$backup->download_backup('../../backups/'.$filename);
    }
}
