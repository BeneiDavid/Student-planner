<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/tasks.php';
require_once BASE_PATH . '/classes/labels.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

$data = [];

$task_id = $_POST['taskId'];
$tasks = new Tasks($l);
$labels = new Labels($l);
$task_query = $tasks->getTask($task_id);
$task_details = mysqli_fetch_assoc($task_query);
$data["task_details"][] = $task_details;

$task_labels_query = $tasks->getTaskLabelsByTaskOrdered($task_id);

while ($row = mysqli_fetch_assoc($task_labels_query)) {
    $label_id = $row['label_id'];
    $label = $labels->getLabel($label_id);
    $label_details = mysqli_fetch_assoc($label);
    $data["label_details"][] = $label_details;
}

$jsonData = json_encode($data);

mysqli_close($l);

header('Content-Type: application/json');
echo $jsonData;

?>