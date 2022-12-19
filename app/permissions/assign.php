<?php
$permission->user_id = $_GET['employee_id'];
$assign = $permission->read();
if ($assign->rowCount() > 0){
    //load the details
    while ($row = $assign->fetch(PDO::FETCH_ASSOC)){
?>









<?php } }  ?>
