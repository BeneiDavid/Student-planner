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

$receiving_user_id = $_POST['receivingUserId'];


$message_send_query = mysqli_query($l, "SELECT * FROM `messages` WHERE `sender_id`='$user_id' AND `receiver_id`='$receiving_user_id' OR 
`sender_id`='$receiving_user_id' AND `receiver_id`='$user_id'");


$update_seen_messages_query = mysqli_query($l, "UPDATE `messages` SET `seen_by_receiver`='1' WHERE `sender_id`='$receiving_user_id' AND `receiver_id`='$user_id'");

$data = [];

while($row = mysqli_fetch_assoc($message_send_query)){
    $data["messages"][] = $row;
}


echo json_encode($data);

mysqli_close($l);








?>