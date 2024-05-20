<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/groups.php';

if(isset($_POST['taskId'])){
    $l = mysqli_connect('localhost', 'root', '', 'student_planner');

    if (!$l) {
    die("Connection failed: " . mysqli_connect_error());
    }

    $task_id = $_POST['taskId'];

    $groups = new Groups($l);
    $group_name = $groups->getGroupNameByTaskId($task_id);

    echo $group_name;

    mysqli_close($l);
}

?>