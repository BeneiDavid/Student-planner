<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$label_id = $_POST['label_id'];
echo $label_id;
$label_name = $_POST['label_name'];
echo $label_name;
$label_color = $_POST['label_color'];
$label_enabled = $_POST["label_enabled"];

if($label_enabled == "true"){
    $label_symbol = isset($_POST['label_symbol']) ? $_POST['label_symbol'] : null;
  }
  else{
    $label_symbol = NULL;
  }

  
$labels_query = mysqli_query($l, "UPDATE `labels` SET `label_name`='$label_name', `label_color`='$label_color', `label_symbol`='$label_symbol' WHERE `label_id`='$label_id'");

echo "success";

mysqli_close($l);


?>