<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/messages.php';
session_start();
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

$other_user_id = $_POST['otherUserId'];

$messages = new Messages($l);

$allMessagesSeen = $messages->areAllMessagesSeenByUser($user_id, $other_user_id);

if(!$allMessagesSeen){
  echo "false";
}
else{
  echo "true";
}

mysqli_close($l);

?>