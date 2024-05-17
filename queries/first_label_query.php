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

$first_label_query = mysqli_query($l, "SELECT `label_id` FROM `labels` WHERE `user_id`='$user_id' LIMIT 1");

$first_label_id = "";

if ($first_label_query) {
  $first_label_data = mysqli_fetch_assoc($first_label_query);
    
  if ($first_label_data) {
    $first_label_id = $first_label_data['label_id'];
  }
  else if(isset($_POST['showGroups'])){
    if($_POST['showGroups'] == "true"){
      $groups_query =  mysqli_query($l, "SELECT group_id FROM `group_members` WHERE `student_id`='$user_id'");

      $groups = [];

      while ($group = mysqli_fetch_assoc($groups_query)) {
        $groups[] = $group;
      }

      $group_task_ids = [];

      foreach ($groups as $group) {
        $group_id = $group['group_id'];
        $groups_tasks_query =  mysqli_query($l, "SELECT task_id FROM `group_tasks` WHERE `group_id`='$group_id'");
    
        while ($group_task = mysqli_fetch_assoc($groups_tasks_query)) {
          $group_task_ids[] = $group_task['task_id'];
        }
      }

      $group_label_ids = [];
      foreach ($group_task_ids as $taskId){
        $groups_task_labels_query =  mysqli_query($l, "SELECT label_id FROM `task_labels` WHERE `task_id`='$taskId'");

        while ($task_label = mysqli_fetch_assoc($groups_task_labels_query)) {
          $group_label_ids[] = $task_label['label_id'];
        }
      }

      $group_label_ids = array_unique($group_label_ids);
      
      foreach ($group_label_ids as $labelId ){
        $groups_labels_query =  mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$labelId' LIMIT 1");
        if ($groups_labels_query) {
          $first_label_data = mysqli_fetch_assoc($groups_labels_query);

          if ($first_label_data) {
            $first_label_id = $first_label_data['label_id'];
            break;
          }
        }
      }
    }
  }
}

  echo $first_label_id;
?>