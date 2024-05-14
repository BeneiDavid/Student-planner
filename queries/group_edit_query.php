<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../user.php';

session_start();
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();
$group_name = $_POST['groupName'];
$group_id = $_POST['groupId'];




mysqli_query($l, "UPDATE `groups` SET `group_name`='$group_name' WHERE `group_id`='$group_id'"); 

if(isset($_POST['studentIds'])){
$student_ids =  $_POST['studentIds'];
$associated_student_ids = array();




$result = mysqli_query($l, "SELECT `student_id` FROM `group_members` WHERE `group_id`='$group_id'");
while ($row = mysqli_fetch_assoc($result)) {
    $associated_student_ids[] = $row['student_id'];
}

foreach ($student_ids as $student_id) {
    // Check if the relation already exists
    $result = mysqli_query($l, "SELECT COUNT(*) FROM `group_members` WHERE `student_id`='".$student_id."' AND `group_id`='".$group_id."'");
    $row = mysqli_fetch_row($result);
    $count = $row[0];
    
    if ($count == 0) {
        // Insert the relation if it doesn't exist
        mysqli_query($l, "INSERT INTO `group_members` SET 
            `membership_id`=NULL,
            `student_id`='".$student_id."',
            `group_id`='".$group_id."'
        ");
    }
}

$not_needed_student_ids = array_diff($associated_student_ids, $student_ids);
if (!empty($not_needed_student_ids)) {
    $not_needed_student_ids_str = implode("','", $not_needed_student_ids);
    mysqli_query($l, "DELETE FROM `group_members` WHERE `group_id`='$group_id' AND `student_id` IN ('$not_needed_student_ids_str')");
    // Ki kell törölni a rendezéseket a felhasználónál
    $task_ids_query =  mysqli_query($l, "SELECT `task_id` FROM `group_tasks` WHERE `group_id`='$group_id'");



    while($task_id_data = mysqli_fetch_assoc($task_ids_query)){
        $task_id = $task_id_data["task_id"];
        mysqli_query($l, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id` IN ('$not_needed_student_ids_str')");
    }
}


}
else{
    mysqli_query($l, "DELETE FROM `group_members` WHERE `group_id`='$group_id'");
    $task_ids_query =  mysqli_query($l, "SELECT `task_id` FROM `group_tasks` WHERE `group_id`='$group_id'");

    while($task_id_data = mysqli_fetch_assoc($task_ids_query)){
        $task_id = $task_id_data["task_id"];
        mysqli_query($l, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id'");
    }
}


mysqli_close($l);
    






?>