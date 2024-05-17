<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

$data = [];

$task_id = $_POST['taskId'];

$task_query = mysqli_query($l, "SELECT * FROM `tasks` WHERE `task_id`='$task_id'");
$task_details = mysqli_fetch_assoc($task_query);
$data["task_details"][] = $task_details;

$task_labels_query = mysqli_query($l, "SELECT * FROM `task_labels` WHERE `task_id`='$task_id' ORDER BY `label_id`");

while ($row = mysqli_fetch_assoc($task_labels_query)) {
    $label_id = $row['label_id'];
    $label = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");
    $label_details = mysqli_fetch_assoc($label);
    $data["label_details"][] = $label_details;
}

$jsonData = json_encode($data);

mysqli_close($l);

header('Content-Type: application/json');
echo $jsonData;

?>