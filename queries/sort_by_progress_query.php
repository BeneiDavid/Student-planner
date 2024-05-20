<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/tasks.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

session_start();

if (!isset($_SESSION['user'])) {
http_response_code(403);
echo json_encode(array('error' => 'User not logged in'));
exit;
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();


$task_id = $_POST['taskId'];
$table_id = $_POST['tableId'];
$tasks = new Tasks($l);

$tasks_query = $tasks->getTaskSortingForUser($task_id, $user_id);

if($tasks_query){
if (mysqli_num_rows($tasks_query) > 0) {
    $tasks->updateByProgressSortingForTask($task_id, $user_id, $table_id);
}
else{
    $tasks->setByProgressSortingForTask($task_id, $user_id, $table_id);
}
}

echo 'success';

?>