<?php
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$label_id = $_POST['label_id'];
$labels_query = mysqli_query($l, "DELETE FROM `labels` WHERE `label_id`='$label_id'");
$label_task_query = mysqli_query($l, "DELETE FROM `task_labels` WHERE `label_id`='$label_id'");

echo "success";

mysqli_close($l);
?>