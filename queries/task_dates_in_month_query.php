<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';

session_start();

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}


$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

$current_year = $_POST['currentYear'];
$current_month = $_POST['currentMonth'];

$date_query = mysqli_query($l, "SELECT `date` FROM `tasks` WHERE YEAR(`date`) = $current_year AND MONTH(`date`) = $current_month AND `user_id` = '$user_id'");

$data = [];

$groups_query =  mysqli_query($l, "SELECT group_id FROM `group_members` WHERE `student_id`='$user_id'");
  if ($groups_query) {
  $groups = [];

  while ($group = mysqli_fetch_assoc($groups_query)) {
    $groups[] = $group;
  }

  $group_task_ids = [];
  
  foreach ($groups as $group) {
    $group_id = $group['group_id'];
    $groups_tasks_query =  mysqli_query($l, "SELECT `task_id` FROM `group_tasks` WHERE `group_id`='$group_id'");

    while ($group_task = mysqli_fetch_assoc($groups_tasks_query)) {
      $group_task_ids[] = $group_task['task_id'];
    }
  }
}

if(!empty($group_task_ids)){
    foreach ($group_task_ids as $group_task_id) {
        $group_task_date_query = mysqli_query($l, "SELECT `date` FROM `tasks` WHERE YEAR(`date`) = $current_year AND MONTH(`date`) = $current_month AND `task_id` = '$group_task_id'");

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