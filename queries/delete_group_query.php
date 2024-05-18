<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$group_id = $_POST['groupId'];

$members_query = mysqli_query($l, "DELETE FROM `group_members` WHERE `group_id`='$group_id'");

$select_tasks_query = mysqli_query($l, "SELECT * FROM `group_tasks` WHERE `group_id`='$group_id'");

while ($task = mysqli_fetch_assoc($select_tasks_query)) {
  $deleted_tasks[] = $task;
}

$group_tasks_query =  mysqli_query($l, "DELETE FROM `group_tasks` WHERE `group_id`='$group_id'");

foreach ($deleted_tasks as $task) {
  $task_id = $task['task_id'];

  $task_labels_query = mysqli_query($l, "DELETE FROM `task_labels` WHERE `task_id`='$task_id'");
  $task_sorting_query = mysqli_query($l, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id'");

}

$group_query = mysqli_query($l, "DELETE FROM `groups` WHERE `group_id`='$group_id'");


echo "success";

mysqli_close($l);









?>