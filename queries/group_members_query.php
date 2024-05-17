<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$group_id = $_POST['groupId'];
$group_query = mysqli_query($l, "SELECT student_id FROM `group_members` WHERE `group_id`='$group_id'");

$data = [];

if (mysqli_num_rows($group_query) > 0) {
    while ($row = mysqli_fetch_assoc($group_query)) {
      $student_id = $row['student_id'];
      $students_query = mysqli_query($l, "SELECT user_id, full_name, username FROM `users` WHERE `user_id`=$student_id AND `reg_confirm`='1'");
      if (mysqli_num_rows($students_query) > 0) {
          while ($student_data = mysqli_fetch_assoc($students_query)) {
              $data["student_data"][] = $student_data;
          }
      }
    }
}

echo json_encode($data);

mysqli_close($l);

?>