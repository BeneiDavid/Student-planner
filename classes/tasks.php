<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
class Tasks {
    // Properties
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection; 
    }

    // Feladat törlése
    public function deleteTask($task_id){
        mysqli_query($this->connection, "DELETE FROM `tasks` WHERE `task_id`='$task_id'");
    }

    // Feladat - címke relációk törlése egy feladatnál 
    public function deleteTaskLabels($task_id){
        mysqli_query($this->connection, "DELETE FROM `task_labels` WHERE `task_id`='$task_id'");
    }

    // Feladatrendezések törlése egy feladatnál
    public function deleteTaskSorting($task_id){
        mysqli_query($this->connection, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id'");
    }

    public function deleteTaskSortingForRemovedMembers($task_id, $removed_member_ids){
        mysqli_query($this->connection, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id` IN ('$removed_member_ids')");
    }

    public function deleteTaskSortingForUser($task_id, $user_id){
        mysqli_query($this->connection, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id`='$user_id'");
    }
}

?>