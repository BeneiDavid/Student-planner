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
    // If user is not logged in, return an error response
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User not logged in'));
    exit;
}


  $user = unserialize($_SESSION['user']);
  $user_id = $user->getId();
  $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `user_id`='$user_id'");

  if (!$labels_query) {
    // If query fails, return an error response
    http_response_code(500); // Internal Server Error
    echo json_encode(array('error' => 'Database query failed'));
    exit;
}


  $data = array();
  while ($row = mysqli_fetch_assoc($labels_query)) {
    $data[] = $row;
  }



  if(isset($_POST['showGroups'])){
    $query_group_labels = $_POST['showGroups'];
    if($query_group_labels == "true"){
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
        $groups_labels_query =  mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$labelId' AND `user_id` != '$user_id'");

        while ($label = mysqli_fetch_assoc($groups_labels_query)) {
          $data[] = $label;
        }
      }
    }
  }

  $jsonData = json_encode($data);

  mysqli_close($l);

  // Send JSON response
  header('Content-Type: application/json');
  echo $jsonData;

  // Close connection
  
?>


