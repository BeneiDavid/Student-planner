<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/groups.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$group_id = $_POST['groupId'];

$groups = new Groups($l);

$groups->deleteGroupWithAssociatedData($group_id);

echo "success";

mysqli_close($l);

?>