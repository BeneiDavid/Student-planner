<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/labels.php';
require_once BASE_PATH . '/classes/groups.php';
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
$labels = new Labels($l);
$groups = new Groups($l);
$tasks = new Tasks($l);

$labels_query = $labels->getUserLabels($user_id);

$data = array();

while ($row = mysqli_fetch_assoc($labels_query)) {
  $data[] = $row;
}

if(isset($_POST['showGroups'])){
  $query_group_labels = $_POST['showGroups'];

  if($query_group_labels == "true"){
    $groups_query = $groups->getGroupsForStudent($user_id);

    $groups = [];

    while ($group = mysqli_fetch_assoc($groups_query)) {
      $groups[] = $group;
    }
    $group_task_ids = [];

    foreach ($groups as $group) {
      $group_id = $group['group_id'];
      $groups_tasks_query = $tasks->getTaskIdsForGroup($group_id);
  
      while ($group_task = mysqli_fetch_assoc($groups_tasks_query)) {
        $group_task_ids[] = $group_task['task_id'];
      }
    }

    $group_label_ids = [];

    foreach ($group_task_ids as $taskId){
      $groups_task_labels_query = $tasks->getAssociatedLabelIds($taskId);

      while ($task_label = mysqli_fetch_assoc($groups_task_labels_query)) {
        $group_label_ids[] = $task_label['label_id'];
      }
    }

    $group_label_ids = array_unique($group_label_ids);

    foreach ($group_label_ids as $labelId ){
      $groups_labels_query = $labels->getGroupLabelsForStudent($labelId, $user_id);

      while ($label = mysqli_fetch_assoc($groups_labels_query)) {
        $data[] = $label;
      }
    }
  }
}

$jsonData = json_encode($data);

mysqli_close($l);

header('Content-Type: application/json');
echo $jsonData;

?>


