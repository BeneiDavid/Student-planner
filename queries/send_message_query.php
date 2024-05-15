<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../user.php';
session_start();
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

$message = $_POST['message'];
$receiving_user_id = $_POST['receivingUserId'];

$currentDate = date('Y-m-d H:i:s'); 
$message_send_query = mysqli_query($l, "INSERT INTO `messages` SET
`message_id`=NULL,
`sender_id`='".$user_id."',
`receiver_id` = '".$receiving_user_id."',
`message_text`='".$message."',
`message_time`='".$currentDate."',
`seen_by_receiver`= 0 

");


echo "success";

mysqli_close($l);









?>