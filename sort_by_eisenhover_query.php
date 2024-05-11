<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'user.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

session_start();
    $user = unserialize($_SESSION['user']);
  $user_id = $user->getId();
  $task_id = $_POST['taskId'];
  $table_id = $_POST['tableId'];

  $tasks_query = mysqli_query($l, "SELECT * FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id` = '$user_id'");

  if($tasks_query){
    if (mysqli_num_rows($tasks_query) > 0) {
        if($table_id == "urgent_important") {
            mysqli_query($l, "UPDATE `task_sorting` SET `eisenhover`='urgent_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($table_id == "urgent_notimportant") {
            mysqli_query($l, "UPDATE `task_sorting` SET `eisenhover`='urgent_not_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($table_id == "noturgent_important") {
            mysqli_query($l, "UPDATE `task_sorting` SET `eisenhover`='not_urgent_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($table_id == "noturgent_notimportant"){
            mysqli_query($l, "UPDATE `task_sorting` SET `eisenhover`='not_urgent_not_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else{
            mysqli_query($l, "UPDATE `task_sorting` SET `eisenhover`='' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'");
        }
    }
    else{
        if($table_id == "urgent_important") {
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='urgent_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else if($table_id == "urgent_notimportant") {
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='urgent_not_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else if($table_id == "noturgent_important") {
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='not_urgent_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else if($table_id == "noturgent_notimportant") {
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='not_urgent_not_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else{
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
            `task_sort_id`=NULL,
            `task_id`='".$task_id."',
            `eisenhover`='',
            `by_progress`='',
            `user_id` = '$user_id'
            ");
        }
    }
  }


  echo 'success';



?>