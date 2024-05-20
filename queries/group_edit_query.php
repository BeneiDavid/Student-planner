<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/groups.php';
require_once BASE_PATH . '/classes/tasks.php';

session_start();
$l = mysqli_connect('localhost', 'root', '', 'student_planner');

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();
$group_name = $_POST['groupName'];
$group_id = $_POST['groupId'];
$groups = new Groups($l);
$tasks = new Tasks($l);

$groups->updateGroupDetails($group_id, $group_name);

if(isset($_POST['studentIds'])){
    $student_ids = $_POST['studentIds'];
    $associated_student_ids = [];
    $associated_student_ids = $groups->getStudentIdsFromGroup($group_id);

    foreach ($student_ids as $student_id) {
        $count = $groups->getMembershipCountForStudent($group_id, $student_id);

        if ($count == 0) {
            $groups->setGroupMembersData($group_id, $student_id);
        }
    }

    $not_needed_student_ids = array_diff($associated_student_ids, $student_ids);

    if (!empty($not_needed_student_ids)) {
        $not_needed_student_ids_str = implode("','", $not_needed_student_ids);
        
        $groups->deleteRemovedMembers($group_id, $not_needed_student_ids_str);
        $tasks_query = $groups->getGroupTasks($group_id);

        while($task_id_data = mysqli_fetch_assoc($tasks_query)){
            $tasks->deleteTaskSortingForRemovedMembers($task_id_data["task_id"], $not_needed_student_ids_str);
        }
    }
}
else{
    $groups->deleteGroupMembers($group_id);
    $tasks_query = $groups->getGroupTasks($group_id);

    while($task_id_data = mysqli_fetch_assoc($tasks_query)){
        $tasks->deleteTaskSorting($task_id_data["task_id"]);
    }
}


mysqli_close($l);
    

?>