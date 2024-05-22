<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/users.php';
require_once BASE_PATH . '/classes/groups.php';

session_start();

$user = unserialize($_SESSION['user']);
$user_id= $user->getId();

$data = [];

$groups = new Groups($l);
$users = new Users($l);

$group_members_data_query = $groups->getGroupMembersDataForStudent($user_id);

if (mysqli_num_rows($group_members_data_query) > 0) {
    while ($group_member_data = mysqli_fetch_assoc($group_members_data_query)) {
        $groups_query = $groups->getGroupDataById($group_member_data["group_id"]);

        while ($group_data = mysqli_fetch_assoc($groups_query)) {
            $data["group_data"][] = $group_data;             

            $teacher_query = $users->getUserIdAndFullName($group_data["group_teacher_id"]);

            while($teacher_data = mysqli_fetch_assoc($teacher_query)){
                $data["teacher_data"][] = $teacher_data;
            }
        }
    }
}

echo json_encode($data);

mysqli_close($l);

?>