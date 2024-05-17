<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';

session_start();

$user = unserialize($_SESSION['user']);
$user_id= $user->getId();

$data = [];
$group_members_data_query = mysqli_query($l, "SELECT * FROM `group_members` WHERE `student_id`='$user_id'");


if (mysqli_num_rows($group_members_data_query) > 0) {
    while ($group_member_data = mysqli_fetch_assoc($group_members_data_query)) {
        $group_id = $group_member_data["group_id"];
        $groups_query = mysqli_query($l, "SELECT * FROM `groups` WHERE `group_id`='$group_id'");

        while ($group_data = mysqli_fetch_assoc($groups_query)) {
            $data["group_data"][] = $group_data;

            $group_teacher_id = $group_data["group_teacher_id"];
                
            $teacher_query = mysqli_query($l, "SELECT `user_id`, `full_name` FROM `users` WHERE `user_id`='$group_teacher_id'");
            


            while($teacher_data = mysqli_fetch_assoc($teacher_query)){
                $data["teacher_data"][] = $teacher_data;
            }

        }
    }
}

echo json_encode($data);

mysqli_close($l);





?>