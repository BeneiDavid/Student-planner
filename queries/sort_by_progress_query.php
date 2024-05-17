<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
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

  $tasks_query = mysqli_query($l, "SELECT * FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id` = '$user_id'");

  if($tasks_query){
    if (mysqli_num_rows($tasks_query) > 0) {
        if($table_id == "to_do_table") {
            mysqli_query($l, "UPDATE `task_sorting` SET `by_progress`='to_do' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($table_id == "in_progress_table") {
            mysqli_query($l, "UPDATE `task_sorting` SET `by_progress`='in_progress' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($table_id == "done_table") {
            mysqli_query($l, "UPDATE `task_sorting` SET `by_progress`='done' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else{
            mysqli_query($l, "UPDATE `task_sorting` SET `by_progress`='' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'");
        }
    }
    else{
        if($table_id == "to_do_table") {
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `by_progress`='to_do',
        `eisenhover`='',
        `user_id` = '$user_id'
        ");
        }
        else if($table_id == "in_progress_table") {
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `by_progress`='in_progress',
        `eisenhover`='',
        `user_id` = '$user_id'
        ");
        }
        else if($table_id == "done_table") {
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `by_progress`='done',
        `eisenhover`='',
        `user_id` = '$user_id'
        ");
        }
        else{
            mysqli_query($l, "INSERT INTO `task_sorting` SET 
            `task_sort_id`=NULL,
            `task_id`='".$task_id."',
            `by_progress`='',
            `eisenhover`='',
            `user_id` = '$user_id'
            ");
        }
    }
  }

  echo 'success';

?>