<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
session_start();
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

$other_user_id = $_POST['otherUserId'];

$message_seen_query = mysqli_query($l, "SELECT `seen_by_receiver` FROM `messages` WHERE `sender_id`='$other_user_id' AND `receiver_id`='$user_id' AND `seen_by_receiver`='0'");

if (mysqli_num_rows($message_seen_query) > 0) {
    echo "false";
}
else{
    echo "true";
}

mysqli_close($l);


?>