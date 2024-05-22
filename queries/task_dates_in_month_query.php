<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/tasks.php';
require_once BASE_PATH . '/classes/groups.php';

session_start();

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();
$tasks = new Tasks($l);
$groups = new Groups($l);

$current_year = $_POST['currentYear'];
$current_month = $_POST['currentMonth'];

$date_query = $tasks->getTaskDatesWithYearAndMonthByUser($current_year, $current_month, $user_id);

$data = [];

$groups_query = $groups->getGroupIdsForStudent($user_id);

if ($groups_query) {
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
}

if(!empty($group_task_ids)){
    foreach ($group_task_ids as $group_task_id) {
        $group_task_date_query = $tasks->getTaskDatesWithYearAndMonthByTask($current_year, $current_month, $group_task_id);
        
        while ($group_task_date = mysqli_fetch_assoc($group_task_date_query)) {
            $data["dates"][] = $group_task_date['date'];
        }
    }
}

if (mysqli_num_rows($date_query) > 0) {
    while ($row = mysqli_fetch_assoc($date_query)) {
        $data["dates"][] = $row['date'];
    }
}

echo json_encode($data);

mysqli_close($l);

?>