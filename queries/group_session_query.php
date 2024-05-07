<?php


session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['groupId'])){
    $_SESSION['group_id'] = $_POST['groupId'];
}
else{
    $_SESSION['group_id'] = "";
}

echo "megy";



?>