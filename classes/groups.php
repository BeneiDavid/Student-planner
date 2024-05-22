<?php
require_once 'tasks.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Groups {
    // Properties
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection; 
    }

    // Csoport törlése
    private function deleteGroup($group_id){
        mysqli_query($this->connection, "DELETE FROM `groups` WHERE `group_id`='$group_id'");
    }

    // Csoport összes tagjának törlése
    public function deleteGroupMembers($group_id){
        mysqli_query($this->connection, "DELETE FROM `group_members` WHERE `group_id`='$group_id'");
    }
    
    // Csoport feladatainak lekérdezése
    public function getGroupTasks($group_id){
        $select_tasks_query = mysqli_query($this->connection, "SELECT * FROM `group_tasks` WHERE `group_id`='$group_id'");

        return $select_tasks_query;
    }

    // Csoport összes feladatának törlése
    private function deleteGroupTasks($group_id){
        mysqli_query($this->connection, "DELETE FROM `group_tasks` WHERE `group_id`='$group_id'");
    }
    
    // Csoport törlése minden hozzá tartozó adattal
    public function deleteGroupWithAssociatedData($group_id){
        
        $this->deleteGroupMembers($group_id);

        $group_tasks_query = $this->getGroupTasks($group_id);

        $tasks_to_delete = [];

        while ($task = mysqli_fetch_assoc($group_tasks_query)) {
            $tasks_to_delete[] = $task;
        }
        
        $this->deleteGroupTasks($group_id);

        foreach ($tasks_to_delete as $task) {
            $task_id = $task['task_id'];
            $tasks = new Tasks($this->connection); 

            $tasks->deleteTaskLabels($task_id);
            $tasks->deleteTaskSorting($task_id);
            $tasks->deleteTask($task_id);
          }
        
        $this->deleteGroup($group_id);
    }

    // Eltávolított tagságok törlése
    public function deleteRemovedMembers($group_id, $members_to_remove){
        mysqli_query($this->connection, "DELETE FROM `group_members` WHERE `group_id`='$group_id' AND `student_id` IN ('$members_to_remove')");
    }

    // Kilépés a csoportból
    public function quitGroup($group_id, $student_id){
        mysqli_query($this->connection, "DELETE FROM `group_members` WHERE `student_id`='$student_id' AND `group_id`='$group_id'");
    }

    // Csoport név lekérdezése feladat azonosítója alapján
    public function getGroupNameByTaskId($task_id){
        $group_id_query = mysqli_query($this->connection, "SELECT `group_id` FROM `group_tasks` WHERE `task_id`='$task_id' LIMIT 1");
        $group_id_fetch = mysqli_fetch_assoc($group_id_query);
        $group_id = $group_id_fetch['group_id'];
        
        $group_name_query = mysqli_query($this->connection, "SELECT `group_name` FROM `groups` WHERE `group_id`='$group_id' LIMIT 1");
        $group_name_fetch = mysqli_fetch_assoc($group_name_query);
        
        return $group_name_fetch['group_name'];
    }

    // Csoporttagok lekérdezése diákok azonosítója alapján
    public function getGroupMembersDataForStudent($student_id){
        $group_members_data_query = mysqli_query($this->connection, "SELECT * FROM `group_members` WHERE `student_id`='$student_id'");
        return $group_members_data_query;
    }

    // Csoport adatainak lekérdezése csoport azonosító alapján
    public function getGroupDataById($group_id){
        $groups_query = mysqli_query($this->connection, "SELECT * FROM `groups` WHERE `group_id`='$group_id'");
        return $groups_query;
    }

    // Csoport adatainak lekérdezése oktató azonosítója alapján
    public function getGroupDataByTeacherId($teacher_id){
        $groups_query = mysqli_query($this->connection, "SELECT * FROM `groups` WHERE `group_teacher_id`='$teacher_id'");
        return $groups_query;
    }

    // Csoport adatainak mentése
    public function saveGroupDetails($teacher_id, $group_name){
        mysqli_query($this->connection, "INSERT INTO `groups` SET 
        `group_id`=NULL,
        `group_teacher_id`='".$teacher_id."',
        `group_name`='".$group_name."'  
        "); 
    }

    // Csoport adatainak frissítése
    public function updateGroupDetails($group_id, $group_name){
        mysqli_query($this->connection, "UPDATE `groups` SET `group_name`='$group_name' WHERE `group_id`='$group_id'"); 
    }

    // Csoporttagok beállítása
    public function setGroupMembersData($group_id, $student_id){
        mysqli_query($this->connection, "INSERT INTO `group_members` SET 
        `membership_id`=NULL,
        `group_id`='".$group_id."',
        `student_id`='".$student_id."'
        ");
    }

    // Csoporttagság számának lekérdezése diák azonosítója alapján
    public function getMembershipCountForStudent($group_id, $student_id){
        $result = mysqli_query($this->connection, "SELECT COUNT(*) FROM `group_members` WHERE `student_id`='".$student_id."' AND `group_id`='".$group_id."'");
        $count = mysqli_fetch_row($result)[0];
        return $count;
    }

    // Diák azonosítók lekérdezése csoport azonosító alapján
    public function getStudentIdsFromGroup($group_id){
        $result = mysqli_query($this->connection, "SELECT `student_id` FROM `group_members` WHERE `group_id`='$group_id'");
        $student_ids = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $student_ids[] = $row['student_id'];
        }

        return $student_ids;
    }

    // Csoportazonosítók lekérdezése adott diákhoz
    public function getGroupIdsForStudent($student_id){
        $groups_query = mysqli_query($this->connection, "SELECT `group_id` FROM `group_members` WHERE `student_id`='$student_id'");
        return $groups_query;
    }

    // Csoporttagság azonosítók lekérdezése
    public function getGroupMemberIds($user_id, $group_id){
        $group_ids_query =  mysqli_query($this->connection, "SELECT group_id FROM `group_members` WHERE `student_id`='$user_id' AND `group_id`='$group_id'");
        return $group_ids_query;
    }

}

?>