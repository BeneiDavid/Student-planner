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

    public function deleteGroupTasks($task_id){
       mysqli_query($this->connection, "DELETE FROM `group_tasks` WHERE `task_id`='$task_id'");
    }

    public function saveTask($user_id, $title, $task_description, $task_color, $date, $start_time, $end_time, $start_time_enabled, $end_time_endabled){
        mysqli_query($this->connection, "INSERT INTO `tasks` SET 
        `task_id`=NULL,
        `user_id`='".$user_id."',
        `title`='".$title."',
        `task_description`='".$task_description."',
        `task_color` = '".$task_color."',
        `date`='".$date."',
        `start_time`='".$start_time."',
        `end_time`='".$end_time."',
        `start_time_enabled`='".$start_time_enabled."',
        `end_time_enabled`='".$end_time_endabled."'
        ");
    }

    public function saveAssociatedLabel($task_id, $label_id){
        mysqli_query($this->connection, "INSERT INTO `task_labels` SET 
      `task_label_id`=NULL,
      `task_id`='".$task_id."',
      `label_id`='".$label_id."'
      ");
    }

    public function saveAssociatedGroup($task_id, $group_id){
        mysqli_query($this->connection, "INSERT INTO `group_tasks` SET 
        `group_task_id`=NULL,
        `task_id`='".$task_id."',
        `group_id`='".$group_id."'
        ");      
    }

    public function getTaskSortingForUser($task_id, $user_id){
        $sorting_query = mysqli_query($this->connection, "SELECT * FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id`='$user_id'");
        return $sorting_query;
    }

    public function setEisenhoverSortingForTask($task_id, $user_id, $sort_by){
        if($sort_by == "urgent_important") {
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='urgent_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else if($sort_by == "urgent_notimportant") {
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='urgent_not_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else if($sort_by == "noturgent_important") {
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='not_urgent_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else if($sort_by == "noturgent_notimportant") {
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `eisenhover`='not_urgent_not_important',
        `by_progress`='',
        `user_id` = '$user_id'
        ");
        }
        else{
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
            `task_sort_id`=NULL,
            `task_id`='".$task_id."',
            `eisenhover`='',
            `by_progress`='',
            `user_id` = '$user_id'
            ");
        }
    }

    public function updateEisenhoverSortingForTask($task_id, $user_id, $sort_by){
        if($sort_by == "urgent_important") {
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `eisenhover`='urgent_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($sort_by == "urgent_notimportant") {
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `eisenhover`='urgent_not_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($sort_by == "noturgent_important") {
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `eisenhover`='not_urgent_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else if($sort_by == "noturgent_notimportant"){
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `eisenhover`='not_urgent_not_important' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'"); 
        }
        else{
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `eisenhover`='' WHERE `task_id`='$task_id' AND `user_id` = '$user_id'");
        }
    }

    public function setByProgressSortingForTask($task_id, $user_id, $sort_by){
        if($sort_by == "to_do_table") {
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `by_progress`='to_do',
        `eisenhover`='',
        `user_id` = '$user_id'
        ");
        }
        else if($sort_by == "in_progress_table") {
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `by_progress`='in_progress',
        `eisenhover`='',
        `user_id` = '$user_id'
        ");
        }
        else if($sort_by == "done_table") {
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
        `task_sort_id`=NULL,
        `task_id`='".$task_id."',
        `by_progress`='done',
        `eisenhover`='',
        `user_id` = '$user_id'
        ");
        }
        else{
            mysqli_query($this->connection, "INSERT INTO `task_sorting` SET 
            `task_sort_id`=NULL,
            `task_id`='".$task_id."',
            `by_progress`='',
            `eisenhover`='',
            `user_id` = '$user_id'
            ");
        }
    }

    public function updateByProgressSortingForTask($task_id, $user_id, $sort_by){
        if($sort_by == "to_do_table") {
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `by_progress`='to_do' WHERE `task_id`='$task_id' AND `user_id`='$user_id'"); 
        }
        else if($sort_by == "in_progress_table") {
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `by_progress`='in_progress' WHERE `task_id`='$task_id' AND `user_id`='$user_id'"); 
        }
        else if($sort_by == "done_table") {
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `by_progress`='done' WHERE `task_id`='$task_id' AND `user_id`='$user_id'"); 
        }
        else{
            mysqli_query($this->connection, "UPDATE `task_sorting` SET `by_progress`='' WHERE `task_id`='$task_id' AND `user_id`='$user_id'");
        }
    }

}

?>