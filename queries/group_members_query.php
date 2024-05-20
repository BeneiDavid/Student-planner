<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/users.php';
require_once BASE_PATH . '/classes/groups.php';
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$group_id = $_POST['groupId'];
$users = new Users($l);
$groups = new Groups($l);

$data = [];

$student_ids = $groups->getStudentIdsFromGroup($group_id);

foreach ($student_ids as $student_id) {
  $student_query = $users->getStudentData($student_id);

  if (mysqli_num_rows($student_query) > 0) {
      while ($student_data = mysqli_fetch_assoc($student_query)) {
          $data["student_data"][] = $student_data;
      }
  }
}

echo json_encode($data);

mysqli_close($l);

?>