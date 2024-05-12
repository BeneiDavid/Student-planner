<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'user.php';
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
  $data = [];


  // Lekérdezés dátum alapján
  if(isset($_POST['date'])){
    $date = $_POST['date'];

    
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

if (!empty($group_task_ids)) {
    $tasks_query = mysqli_query($l, "SELECT * FROM `tasks` WHERE (`user_id`='$user_id' AND `date`='$date') OR (`task_id` IN (" . implode(',', $group_task_ids) . ") AND `date`='$date')");
}
else{
  $tasks_query = mysqli_query($l, "SELECT * FROM `tasks` WHERE (`user_id`='$user_id' AND `date`='$date')");
}
    

  
    if (!$tasks_query) {
      http_response_code(500); 
      echo json_encode(array('error' => 'Database query failed'));
      exit;
    }

    if (mysqli_num_rows($tasks_query) > 0) {
      while ($row = mysqli_fetch_assoc($tasks_query)) {
          $task_id = $row['task_id'];
          
          $tasks_labels_query = mysqli_query($l, "SELECT * FROM `task_labels` WHERE `task_id`='$task_id'");

          $data["tasks"][] = $row;

          if (mysqli_num_rows($tasks_labels_query) > 0) {
              while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                  $label_id = $label_row['label_id'];
                  $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");

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


    $task_labels_query =  mysqli_query($l, "SELECT * FROM `task_labels` WHERE `label_id`='$label_id'");

    if (mysqli_num_rows($task_labels_query) > 0) {
      while ($row = mysqli_fetch_assoc($task_labels_query)) {
        $task_id = $row['task_id'];
        $tasks_query = mysqli_query($l, "SELECT * FROM `tasks` WHERE `task_id`='$task_id'");

        if(mysqli_num_rows($tasks_query) > 0){
          while($task = mysqli_fetch_assoc($tasks_query)){

          $tasks_labels_query = mysqli_query($l, "SELECT * FROM `task_labels` WHERE `task_id`='$task_id'");

          $data["tasks"][] = $task;

          if (mysqli_num_rows($tasks_labels_query) > 0) {
            while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
              $label_id = $label_row['label_id'];
              $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");

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
} // Csoport feladatainak listázása - tanár
else if(isset($_POST['groupId'])){

  $group_id = $_SESSION['group_id'];
  $task_ids_query = mysqli_query($l, "SELECT task_id FROM `group_tasks` WHERE `group_id`='$group_id'");

  while ($ids = mysqli_fetch_assoc($task_ids_query)) {
    $task_ids[] = $ids['task_id'];
  }

  if (!empty($task_ids)) {
  
    $task_ids_string = implode(',', $task_ids);

    $tasks_query = mysqli_query($l, "SELECT * FROM `tasks` WHERE `user_id`='$user_id' AND `task_id` IN ($task_ids_string)");

  if (!$tasks_query) {
    http_response_code(500); 
    echo json_encode(array('error' => 'Database query failed'));
    exit;
  }

  if (mysqli_num_rows($tasks_query) > 0) {
    while ($row = mysqli_fetch_assoc($tasks_query)) {
        $task_id = $row['task_id'];
        
        $tasks_labels_query = mysqli_query($l, "SELECT * FROM `task_labels` WHERE `task_id`='$task_id'");
        $task_sorting_query = mysqli_query($l, "SELECT * FROM `task_sorting` WHERE `task_id`='$task_id'");

        $data["tasks"][] = $row;

        if (mysqli_num_rows($tasks_labels_query) > 0) {
            while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                $label_id = $label_row['label_id'];
                $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");

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

  
  $groups_query =  mysqli_query($l, "SELECT group_id FROM `group_members` WHERE `student_id`='$user_id'");
  if ($groups_query) {
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
}
    
if (!empty($group_task_ids)) {
  $tasks_query = mysqli_query($l, "SELECT * FROM `tasks` WHERE `user_id`='$user_id' OR (`task_id` IN (" . implode(',', $group_task_ids) . "))");
}
else{
  $tasks_query = mysqli_query($l, "SELECT * FROM `tasks` WHERE `user_id`='$user_id'");
}



  if (!$tasks_query) {
    http_response_code(500); 
    echo json_encode(array('error' => 'Database query failed'));
    exit;
  }

  if (mysqli_num_rows($tasks_query) > 0) {
    while ($row = mysqli_fetch_assoc($tasks_query)) {
        $task_id = $row['task_id'];
        
        $tasks_labels_query = mysqli_query($l, "SELECT * FROM `task_labels` WHERE `task_id`='$task_id'");
        $task_sorting_query = mysqli_query($l, "SELECT * FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id`='$user_id'");

        $data["tasks"][] = $row;

        if (mysqli_num_rows($tasks_labels_query) > 0) {
            while ($label_row = mysqli_fetch_assoc($tasks_labels_query)) {
                $label_id = $label_row['label_id'];
                $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");

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





