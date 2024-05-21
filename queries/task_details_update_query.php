<?php
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/tasks.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['taskAddData']))
{
  session_start();
  $l = mysqli_connect('localhost', 'root', '', 'student_planner');
  $task_id = $_POST['taskId'];
  $task_name = mysqli_real_escape_string($l, $_POST['taskName']);
  $task_color = $_POST["colorpicker"];
  $label_ids_json = $_POST["jsonIdList"];
  $label_ids = json_decode($label_ids_json);
  $date = $_POST["date"];
  $startTime = $_POST["startTime"];
  $endTime = $_POST["endTime"];
  $enable_start_time = $_POST["enableStartTime"];
  $enable_end_time = $_POST["enableEndTime"];
  $task_description = $_POST["taskDescription"];

  $user = unserialize($_SESSION['user']);

  $user_id = $user->getId();

  if($enable_start_time == "true"){
    $enable_start_time = 1;
  }
  else{
    $enable_start_time = 0;
  }

  if($enable_end_time == "true"){
    $enable_end_time = 1;
  }
  else{
    $enable_end_time = 0;
  }

  $tasks = new Tasks($l);

  $tasks->updateTask($task_id, $user_id, $task_name, $task_description, $task_color, $date, $startTime, $endTime, $enable_start_time, $enable_end_time);

  $associated_label_ids = array();

  $result = $tasks->getAssociatedLabelIds($task_id);

  while ($row = mysqli_fetch_assoc($result)) {
      $associated_label_ids[] = $row['label_id'];
  }

  foreach ($label_ids as $label_id) {
      $count = $tasks->getTaskLabelRelationCount($task_id, $label_id);
      if ($count == 0) {
        $tasks->addLabelToTask($task_id, $label_id);
      }
  }
  
  $not_needed_label_ids = array_diff($associated_label_ids, $label_ids);
  
  if (!empty($not_needed_label_ids)) {
      $not_needed_label_ids_str = implode("','", $not_needed_label_ids);
      $tasks->removeLabelsFromTask($task_id, $not_needed_label_ids_str);
  }
}

?>