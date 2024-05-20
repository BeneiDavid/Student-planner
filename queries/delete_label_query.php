<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/labels.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$labels = new Labels($l);
$label_id = $_POST['label_id'];
$labels->deleteTaskLabelRelation($label_id);
$labels->deleteLabel($label_id);

echo "success";

mysqli_close($l);
?>