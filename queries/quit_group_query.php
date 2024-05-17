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

$group_id = $_POST['groupId'];
$members_query = mysqli_query($l, "DELETE FROM `group_members` WHERE `student_id`='$user_id' AND `group_id`='$group_id'");

$select_tasks_query = mysqli_query($l, "SELECT * FROM `group_tasks` WHERE `group_id`='$group_id'");

while ($task = mysqli_fetch_assoc($select_tasks_query)) {
  $associated_tasks[] = $task;
}


foreach ($associated_tasks as $task) {
  $task_id = $task['task_id'];

  $task_sorting_query = mysqli_query($l, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id`='$user_id' ");

}


echo "success";

mysqli_close($l);





?>