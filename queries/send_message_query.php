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

$message = $_POST['message'];
$receiving_user_id = $_POST['receivingUserId'];

$messages = new Messages($l);
$messages->sendMessage($user_id, $receiving_user_id, $message);

echo "success";

mysqli_close($l);

?>