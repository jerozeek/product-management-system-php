<?php
header('Content-Type:application/json');
require_once '../autoload.php';
$database = new DB();
$db = $database->connect();
$backup = new Backup($db);
if (isset($_GET['filename'])){
    $file = $_GET['filename'];
    $backup->download_backup('../backups/'.$file);
}

if (isset($_GET['delete_file'])){
    $file = $_GET['delete_file'];
    if ($backup->delete_backup('../backups/'.$file)){
        Session::set('success', "<div class='alert alert-success wow fadeInUp animated' data-wow-duration='500ms' data-wow-delay='900ms'><button class='close' data-dismiss='alert'>&times;</button><strong>Success: </strong> Backup Deleted Successfully</div>");
        Redirect::to('backup/backup.php');
    }

}
