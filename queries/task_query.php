<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/groups.php';
require_once BASE_PATH . '/classes/tasks.php';
require_once BASE_PATH . '/classes/labels.php';
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
$groups = new Groups($l);
$tasks = new Tasks($l);
$labels = new Labels($l);

$data = [];

// Lekérdezés dátum alapján
if(isset($_POST['date'])){
  $date = $_POST['date'];

  $groups_query = $groups->getGroupIdsForStudent($user_id);

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

  if (!empty($group_task_ids)) {
    $tasks_query = $tasks->getUserAndGroupTasksForDate($user_id, $date, $group_task_ids);
  }
  else{
    $tasks_query = $tasks->getTaskByUserAndDate($user_id, $date);
  }
  
  if (!$tasks_query) {
    http_response_code(500); 
    echo json_encode(array('error' => 'Database query failed'));
    exit;
  }

  if (mysqli_num_rows($tasks_query) > 0) {
    while ($row = mysqli_fetch_assoc($tasks_query)) {
        $task_id = $row['task_id'];
        $tasks_labels_query = $tasks->getTaskLabelsByTask($task_id);

        $data["tasks"][] = $row;

        if (mysqli_num_rows($tasks_labels_query) > 0) {
            while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                $label_id = $label_row['label_id'];

                $labels_query = $labels->getLabel($label_id);
                $data["task_labels"][] = $label_row;

                if(mysqli_num_rows($labels_query) > 0){
                  while($label = mysqli_fetch_assoc($labels_query)){
                    $data["labels"][] = $label;
                  }
                }
            }
        }
    }
  }
} // Lekérdezés címke alapján
  else if(isset($_POST['labelId'])){
    $label_id = $_POST['labelId'];

    $group_task_ids = [];
    $user_tasks = [];
    $group_tasks = [];
    $groups_query = $groups->getGroupIdsForStudent($user_id);

    if ($groups_query) {
      $groups = [];
      
      while ($group = mysqli_fetch_assoc($groups_query)) {
        $groups[] = $group;
      }
    
      foreach ($groups as $group) {
        $group_id = $group['group_id'];
        $groups_tasks_query = $tasks->getTaskIdsForGroup($group_id);

        while ($group_task = mysqli_fetch_assoc($groups_tasks_query)) {
          $group_task_ids[] = $group_task['task_id'];
        }
      }
    }

    $task_labels_query = $tasks->getTaskLabelsByLabel($label_id);

    $user_task_query = $tasks->getTaskIdsForUser($user_id);

    while ($user_task = mysqli_fetch_assoc($user_task_query)) {
      $user_task_ids[] = $user_task['task_id'];
    }

    $group_tasks[] = $tasks->getTasksByGroupTaskIds($group_task_ids);

    if (mysqli_num_rows($task_labels_query) > 0) {
      while ($row = mysqli_fetch_assoc($task_labels_query)) {
        if(in_array($row['task_id'], $group_task_ids) || in_array($row['task_id'], $user_task_ids)){
          
          $task_id = $row['task_id'];
          $tasks_query = $tasks->getTask($task_id);

          if(mysqli_num_rows($tasks_query) > 0){
            while($task = mysqli_fetch_assoc($tasks_query)){
  
  
              $tasks_labels_query = $tasks->getTaskLabelsByTask($task_id);
  
              $data["tasks"][] = $task;
  
              if (mysqli_num_rows($tasks_labels_query) > 0) {
                while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                  $label_id = $label_row['label_id'];
  
                  $labels_query = $labels->getLabel($label_id);
  
  
                  $data["task_labels"][] = $label_row;
  
                  if(mysqli_num_rows($labels_query) > 0){
                    while($label = mysqli_fetch_assoc($labels_query)){
                      $data["labels"][] = $label;
                    }
                  }
                }
              }
            }
          }
        }
      }
    }


} // Csoport feladatainak listázása
else if(isset($_POST['groupId'])){
  // Oktató
  $group_id = $_SESSION['group_id'];
  if($user->getUserType() == "teacher"){

    $task_ids_query = $tasks->getTaskIdsForGroup($group_id);

    while ($ids = mysqli_fetch_assoc($task_ids_query)) {
      $task_ids[] = $ids['task_id'];
    }

    if (!empty($task_ids)) {
    
        $task_ids_string = implode(',', $task_ids);
        $tasks_query = $tasks->getTasksByTaskIds($user_id, $task_ids_string);

      if (!$tasks_query) {
        http_response_code(500); 
        echo json_encode(array('error' => 'Database query failed'));
        exit;
      }

      if (mysqli_num_rows($tasks_query) > 0) {
        while ($row = mysqli_fetch_assoc($tasks_query)) {
            $task_id = $row['task_id'];
            $tasks_labels_query = $tasks->getTaskLabelsByTask($task_id);
            $task_sorting_query = $tasks->getTaskSortingForTask($task_id);


            $data["tasks"][] = $row;

            if (mysqli_num_rows($tasks_labels_query) > 0) {
                while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                    $label_id = $label_row['label_id'];
                    $labels_query = $labels->getLabel($label_id);

                    $data["task_labels"][] = $label_row;

                    if(mysqli_num_rows($labels_query) > 0){
                      while($label = mysqli_fetch_assoc($labels_query)){
                        $data["labels"][] = $label;
                      }
                    }
                }
            }

            if (mysqli_num_rows($task_sorting_query) > 0) {
              while ($task_sorting_row = mysqli_fetch_assoc($task_sorting_query)) {
                  $data["task_sorting_row"][] = $task_sorting_row;
              }
            }
        }
      }
    }
  }
  // Hallgató
  else{
    $groups_query = $groups->getGroupMemberIds($user_id, $group_id);
    $group_task_ids = [];

    if ($groups_query) {
      $groups = [];

      while ($group = mysqli_fetch_assoc($groups_query)) {
        $groups[] = $group;
      }
     
      foreach ($groups as $group) {
        $group_id = $group['group_id'];
        $groups_tasks_query = $tasks->getTaskIdsForGroup($group_id);

        while ($group_task = mysqli_fetch_assoc($groups_tasks_query)) {
          $group_task_ids[] = $group_task['task_id'];
        }
      }
    }
  }
  if (!empty($group_task_ids)) {
    $tasks_query = $tasks->getTasksByGroupTaskIds($group_task_ids);

    if (!$tasks_query) {
      http_response_code(500); 
      echo json_encode(array('error' => 'Database query failed'));
      exit;
    }

    if (mysqli_num_rows($tasks_query) > 0) {
      while ($row = mysqli_fetch_assoc($tasks_query)) {
          $task_id = $row['task_id'];
          $tasks_labels_query = $tasks->getTaskLabelsByTask($task_id);
          $task_sorting_query = $tasks->getTaskSortingForUser($task_id, $user_id);

          $data["tasks"][] = $row;

          if (mysqli_num_rows($tasks_labels_query) > 0) {
              while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                  $label_id = $label_row['label_id'];
                  $labels_query = $labels->getLabel($label_id);


                  $data["task_labels"][] = $label_row;

                  if(mysqli_num_rows($labels_query) > 0){
                    while($label = mysqli_fetch_assoc($labels_query)){
                      $data["labels"][] = $label;
                    }
                  }
              }
          }
          if (mysqli_num_rows($task_sorting_query) > 0) {
            while ($task_sorting_row = mysqli_fetch_assoc($task_sorting_query)) {
                $data["task_sorting_row"][] = $task_sorting_row;
            }
        }
      }
    }
  }
}// Összes feladat listázása
else{
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
    
  if (!empty($group_task_ids)) {
    $tasks_query = $tasks->getTasksForUserAndGroups($user_id, $group_task_ids);
  }
  else{
    $tasks_query = $tasks->getAllTasksForUser($user_id);
  }

  if (!$tasks_query) {
    http_response_code(500); 
    echo json_encode(array('error' => 'Database query failed'));
    exit;
  }

  if (mysqli_num_rows($tasks_query) > 0) {
    while ($row = mysqli_fetch_assoc($tasks_query)) {
        $task_id = $row['task_id'];
        $tasks_labels_query = $tasks->getTaskLabelsByTask($task_id);
        $task_sorting_query = $tasks->getTaskSortingForUser($task_id, $user_id);
        $data["tasks"][] = $row;

        if (mysqli_num_rows($tasks_labels_query) > 0) {
            while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                $label_id = $label_row['label_id'];
                $labels_query = $labels->getLabel($label_id);
                $data["task_labels"][] = $label_row;

                if(mysqli_num_rows($labels_query) > 0){
                  while($label = mysqli_fetch_assoc($labels_query)){
                    $data["labels"][] = $label;
                  }
                }
            }
        }

        if (mysqli_num_rows($task_sorting_query) > 0) {
          while ($task_sorting_row = mysqli_fetch_assoc($task_sorting_query)) {
              $data["task_sorting_row"][] = $task_sorting_row;
          }
      }
    }
  }
}

$jsonData = json_encode($data);

mysqli_close($l);

header('Content-Type: application/json');
echo $jsonData;
  
?>