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
        $task_query = mysqli_query($this->connection, "DELETE FROM `tasks` WHERE `task_id`='$task_id'");
    }

    // Feladat - címke relációk törlése egy feladatnál 
    public function deleteTaskLabels($task_id){
        $task_labels_query = mysqli_query($this->connection, "DELETE FROM `task_labels` WHERE `task_id`='$task_id'");
    }

    // Feladatrendezések törlése egy feladatnál
    public function deleteTaskSorting($task_id){
        $task_sorting_query = mysqli_query($this->connection, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id'");
    }

    
}

?>