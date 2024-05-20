<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/users.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

$data = [];
$users = new Users($l);
$students_query = $users->getAllConfirmedStudents();

if (mysqli_num_rows($students_query) > 0) {
    while ($student_data = mysqli_fetch_assoc($students_query)) {
        $data["student_data"][] = $student_data;
    }
}

echo json_encode($data);

mysqli_close($l);

?>