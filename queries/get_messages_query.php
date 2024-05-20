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

$receiving_user_id = $_POST['receivingUserId'];


$messages = new Messages($l);

$messages->updateMessagesSeenByUser($user_id, $receiving_user_id);

$data = [];
$data["messages"] = $messages->getMessages($user_id, $receiving_user_id);

echo json_encode($data);

mysqli_close($l);

?>