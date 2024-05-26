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

$student_ids = $_POST['studentIds'];
$message =  mysqli_real_escape_string($l, $_POST['message']);

$messages = new Messages($l);

if(isset($student_ids) && is_array($student_ids)) {
    foreach($student_ids as $student_id) {
      $messages->sendMessage($user_id, $student_id, $message);
    }
}

echo "success";

?>

