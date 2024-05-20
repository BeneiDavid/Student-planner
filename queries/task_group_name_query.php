<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_POST['taskId'])){
    $l = mysqli_connect('localhost', 'root', '', 'student_planner');

    if (!$l) {
    die("Connection failed: " . mysqli_connect_error());
    }
    $task_id = $_POST['taskId'];
    $group_id_query = mysqli_query($l, "SELECT `group_id` FROM `group_tasks` WHERE `task_id`='$task_id' LIMIT 1");
    $group_id_fetch = mysqli_fetch_assoc($group_id_query);
    $group_id = $group_id_fetch['group_id'];

    
    $group_name_query = mysqli_query($l, "SELECT `group_name` FROM `groups` WHERE `group_id`='$group_id' LIMIT 1");
    $group_name_fetch = mysqli_fetch_assoc($group_name_query);
    
    echo $group_name_fetch['group_name'];

    mysqli_close($l);
}

?>