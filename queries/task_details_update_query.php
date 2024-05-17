<?php
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
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


    mysqli_query($l, "UPDATE `tasks` SET 
        `user_id`='".$user_id."',
        `title`='".$task_name."',
        `task_description`='".$task_description."',
        `task_color` = '".$task_color."',
        `date`='".$date."',
        `start_time`='".$startTime."',
        `end_time`='".$endTime."',
        `start_time_enabled`='".$enable_start_time."',
        `end_time_enabled`='".$enable_end_time."'
        WHERE `task_id` = ".$task_id);

        $associated_label_ids = array();

        $result = mysqli_query($l, "SELECT `label_id` FROM `task_labels` WHERE `task_id`='$task_id'");
        while ($row = mysqli_fetch_assoc($result)) {
            $associated_label_ids[] = $row['label_id'];
        }

        foreach ($label_ids as $label_id) {
            // Check if the relation already exists
            $result = mysqli_query($l, "SELECT COUNT(*) FROM `task_labels` WHERE `task_id`='".$task_id."' AND `label_id`='".$label_id."'");
            $row = mysqli_fetch_row($result);
            $count = $row[0];
            
            if ($count == 0) {
                // Insert the relation if it doesn't exist
                mysqli_query($l, "INSERT INTO `task_labels` SET 
                    `task_label_id`=NULL,
                    `task_id`='".$task_id."',
                    `label_id`='".$label_id."'
                ");
            }
        }
        
        $not_needed_label_ids = array_diff($associated_label_ids, $label_ids);
        if (!empty($not_needed_label_ids)) {
            $not_needed_label_ids_str = implode("','", $not_needed_label_ids);
            mysqli_query($l, "DELETE FROM `task_labels` WHERE `task_id`='$task_id' AND `label_id` IN ('$not_needed_label_ids_str')");
        }


}

?>