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
        $group_query = mysqli_query($this->connection, "DELETE FROM `groups` WHERE `group_id`='$group_id'");
    }

    // Csoport összes tagjának törlése
    private function deleteGroupMembers($group_id){
        $members_query = mysqli_query($this->connection, "DELETE FROM `group_members` WHERE `group_id`='$group_id'");
    }
    
    // Csoport feladatainak lekérdezése
    public function getGroupTasks($group_id){
        $select_tasks_query = mysqli_query($this->connection, "SELECT * FROM `group_tasks` WHERE `group_id`='$group_id'");
        $group_tasks = [];
        
        while ($task = mysqli_fetch_assoc($select_tasks_query)) {
            $group_tasks[] = $task;
        }

        return $group_tasks;
    }

    // Csoport összes feladatának törlése
    private function deleteGroupTasks($group_id){
        $group_tasks_query =  mysqli_query($this->connection, "DELETE FROM `group_tasks` WHERE `group_id`='$group_id'");
    }
    
    // Csoport törlése minden hozzá tartozó adattal
    public function deleteGroupWithAssociatedData($group_id){
        
        $this->deleteGroupMembers($group_id);
        
        $tasks_to_delete = $this->getGroupTasks($group_id);

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

    // Csoport név lekérdezése feladat azonosítója alapján
    public function getGroupNameByTaskId($task_id){
        $group_id_query = mysqli_query($this->connection, "SELECT `group_id` FROM `group_tasks` WHERE `task_id`='$task_id' LIMIT 1");
        $group_id_fetch = mysqli_fetch_assoc($group_id_query);
        $group_id = $group_id_fetch['group_id'];
        
        $group_name_query = mysqli_query($this->connection, "SELECT `group_name` FROM `groups` WHERE `group_id`='$group_id' LIMIT 1");
        $group_name_fetch = mysqli_fetch_assoc($group_name_query);
        
        return $group_name_fetch['group_name'];
    }

    public function getGroupMembersDataForUser($user_id){
        $group_members_data_query = mysqli_query($this->connection, "SELECT * FROM `group_members` WHERE `student_id`='$user_id'");
        return $group_members_data_query;
    }

    public function getGroupDataById($group_id){
        $groups_query = mysqli_query($this->connection, "SELECT * FROM `groups` WHERE `group_id`='$group_id'");
        return $groups_query;
    }

    public function getGroupDataByTeacherId($teacher_id){
        $groups_query = mysqli_query($this->connection, "SELECT * FROM `groups` WHERE `group_teacher_id`='$teacher_id'");
        return $groups_query;
    }
}

?>