<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/groups.php';
require_once BASE_PATH . '/classes/tasks.php';
session_start();
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();
$groups = new Groups($l);
$tasks = new Tasks($l);
$group_id = $_POST['groupId'];

$groups->quitGroup($group_id, $user_id);

$select_tasks_query = $groups->getGroupTasks($group_id);

while ($task = mysqli_fetch_assoc($select_tasks_query)) {
  $associated_tasks[] = $task;
}

foreach ($associated_tasks as $task) {
  $task_id = $task['task_id'];

  $tasks->deleteTaskSortingForUser($task_id, $user_id);
}

echo "success";

mysqli_close($l);

?>