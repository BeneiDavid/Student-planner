<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/labels.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$label_id = $_POST['label_id'];
$label_name = $_POST['label_name'];
$label_color = $_POST['label_color'];
$label_enabled = $_POST["label_enabled"];

if($label_enabled == "true"){
    $label_symbol = isset($_POST['label_symbol']) ? $_POST['label_symbol'] : null;
}
else{
  $label_symbol = NULL;
}

$labels = new Labels($l);
$labels->updateLabel($label_id, $label_name, $label_color, $label_symbol);

echo "success";

mysqli_close($l);

?>