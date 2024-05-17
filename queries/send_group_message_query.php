<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config.php';
require_once BASE_PATH . '/classes/user.php';
session_start();
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

$student_ids = $_POST['studentIds'];
$message = $_POST['message'];
$currentDate = date('Y-m-d H:i:s'); 

if(isset($student_ids) && is_array($student_ids)) {
    foreach($student_ids as $student_id) {
        $message_send_query = mysqli_query($l, "INSERT INTO `messages` SET
        `message_id`=NULL,
        `sender_id`='".$user_id."',
        `receiver_id` = '".$student_id."',
        `message_text`='".$message."',
        `message_time`='".$currentDate."',
        `seen_by_receiver`= 0 
        ");
    }
}

echo "success";

?>