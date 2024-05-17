<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

$data = [];
$students_query = mysqli_query($l, "SELECT user_id, full_name, username FROM `users` WHERE `user_type`='student' AND `reg_confirm`='1'");

if (mysqli_num_rows($students_query) > 0) {
    while ($student_data = mysqli_fetch_assoc($students_query)) {
        $data["student_data"][] = $student_data;
    }
}

echo json_encode($data);

mysqli_close($l);

?>