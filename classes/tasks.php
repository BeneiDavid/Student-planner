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

    // Feladatrendezések törlése eltávolított tagoknál
    public function deleteTaskSortingForRemovedMembers($task_id, $removed_member_ids){
        mysqli_query($this->connection, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id` IN ('$removed_member_ids')");
    }

    // Feladatrendezések törlése egy felhasználónál
    public function deleteTaskSortingForUser($task_id, $user_id){
        mysqli_query($this->connection, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id`='$user_id'");
    }

    // Csoportfeladat reláció törlése
    public function deleteGroupTasks($task_id){
       mysqli_query($this->connection, "DELETE FROM `group_tasks` WHERE `task_id`='$task_id'");
    }

     // Feladat mentése
    public function saveTask($user_id, $title, $task_description, $task_color, $date, $start_time, $end_time, $start_time_enabled, $end_time_enabled){
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
        `end_time_enabled`='".$end_time_enabled."'
        ");
    }

    // Feladat frissítése
    public function updateTask($task_id, $user_id, $title, $task_description, $task_color, $date, $start_time, $end_time, $start_time_enabled, $end_time_enabled){
        mysqli_query($this->connection, "UPDATE `tasks` SET 
        `user_id`='".$user_id."',
        `title`='".$title."',
        `task_description`='".$task_description."',
        `task_color` = '".$task_color."',
        `date`='".$date."',
        `start_time`='".$start_time."',
        `end_time`='".$end_time."',
        `start_time_enabled`='".$start_time_enabled."',
        `end_time_enabled`='".$end_time_enabled."'
        WHERE `task_id` = ".$task_id);
    }

    // Feladat lekérdezése
    public function getTask($task_id){
        $task_query = mysqli_query($this->connection, "SELECT * FROM `tasks` WHERE `task_id`='$task_id'");
        return $task_query;
    }

    // Felhasználó feladatazonosítóinak lekérdezése
    public function getTaskIdsForUser($user_id){
        $task_query = mysqli_query($this->connection, "SELECT `task_id` FROM `tasks` WHERE `user_id`='$user_id'");
        return $task_query;
    }

    // Felhasználó feladatainak lekérdezése
    public function getAllTasksForUser($user_id){
        $tasks_query = mysqli_query($this->connection, "SELECT * FROM `tasks` WHERE `user_id`='$user_id'");
        return $tasks_query;
    }

    // Felhasználó- és csoport feladatainak lekérdezése
    public function getTasksForUserAndGroups($user_id, $group_task_ids){
        $tasks_query = mysqli_query($this->connection, "SELECT * FROM `tasks` WHERE `user_id`='$user_id' OR (`task_id` IN (" . implode(',', $group_task_ids) . "))");
        return $tasks_query;
    }

    // Feladat címke reláció lekérdezése feladat alapján létrehozás szerint
    public function getTaskLabelsByTaskOrdered($task_id){
        $task_labels_query = mysqli_query($this->connection, "SELECT * FROM `task_labels` WHERE `task_id`='$task_id' ORDER BY `label_id`");
        return $task_labels_query;
    }

    // Feladat címke reláció lekérdezése feladat alapján
    public function getTaskLabelsByTask($task_id){
        $task_labels_query = mysqli_query($this->connection, "SELECT * FROM `task_labels` WHERE `task_id`='$task_id'");
        return $task_labels_query;
    }

    // Feladat - címke reláció lekérdezése címke alapján
    public function getTaskLabelsByLabel($label_id){
        $task_labels_query = mysqli_query($this->connection, "SELECT * FROM `task_labels` WHERE `label_id`='$label_id'");
        return $task_labels_query;
    }

    // Feladat - címke reláció mentése
    public function saveAssociatedLabel($task_id, $label_id){
        mysqli_query($this->connection, "INSERT INTO `task_labels` SET 
      `task_label_id`=NULL,
      `task_id`='".$task_id."',
      `label_id`='".$label_id."'
      ");
    }

    // Feladat - csoport reláció mentése
    public function saveAssociatedGroup($task_id, $group_id){
        mysqli_query($this->connection, "INSERT INTO `group_tasks` SET 
        `group_task_id`=NULL,
        `task_id`='".$task_id."',
        `group_id`='".$group_id."'
        ");      
    }

    // Felhasználó feladatrendezéseinek lekérdezése
    public function getTaskSortingForUser($task_id, $user_id){
        $sorting_query = mysqli_query($this->connection, "SELECT * FROM `task_sorting` WHERE `task_id`='$task_id' AND `user_id`='$user_id'");
        return $sorting_query;
    }

    // Feladat feladatrendezéseinek lekérdezése
    public function getTaskSortingForTask($task_id){
        $task_sorting_query = mysqli_query($this->connection, "SELECT * FROM `task_sorting` WHERE `task_id`='$task_id'");
        return $task_sorting_query;
    }

    // Eisenhover rendezés mentése
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

    // Eisenhover rendezés frissítése
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

    // Folyamat alapú rendezés mentése
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

    // Folyamat alapú rendezés frissítése
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

    // Feladathoz rendelt címkék azonosítójának lekérdezése
    public function getAssociatedLabelIds($task_id){
        $label_ids_query = mysqli_query($this->connection, "SELECT `label_id` FROM `task_labels` WHERE `task_id`='$task_id'");
        return  $label_ids_query;
    }

    // Feladat címkéinek eltávolítása
    public function removeLabelsFromTask($task_id, $labels_to_remove){
        mysqli_query($this->connection, "DELETE FROM `task_labels` WHERE `task_id`='$task_id' AND `label_id` IN ('$labels_to_remove')");
    }

    // Címke rendelése feladathoz
    public function addLabelToTask($task_id, $label_id){
        mysqli_query($this->connection, "INSERT INTO `task_labels` SET 
              `task_label_id`=NULL,
              `task_id`='".$task_id."',
              `label_id`='".$label_id."'
          ");
    }

    // Feladathoz rendelt címkék számának lekérdezése
    public function getTaskLabelRelationCount($task_id, $label_id){
        $task_labels_query = mysqli_query($this->connection, "SELECT COUNT(*) FROM `task_labels` WHERE `task_id`='".$task_id."' AND `label_id`='".$label_id."'");
        $task_label_data = mysqli_fetch_row($task_labels_query);
        $relation_count = $task_label_data[0];
        return $relation_count;
    }

    // Csoport feladatazonosítóinak lekérdezése
    public function getTaskIdsForGroup($group_id){
        $group_task_ids_query =  mysqli_query($this->connection, "SELECT `task_id` FROM `group_tasks` WHERE `group_id`='$group_id'");
        return $group_task_ids_query;
    }

    // Feladatok lekérdezése adott évre és hónapra felhaszáló azonosító alapján
    public function getTaskDatesWithYearAndMonthByUser($year, $month, $user_id){
        $date_query = mysqli_query($this->connection, "SELECT `date` FROM `tasks` WHERE YEAR(`date`) = $year AND MONTH(`date`) = $month AND `user_id` = '$user_id'");
        return $date_query;
    }

    // Feladatok lekérdezése adott évre és hónapra feladat azonosító alapján
    public function getTaskDatesWithYearAndMonthByTask($year, $month, $task_id){
        $task_date_query = mysqli_query($this->connection, "SELECT `date` FROM `tasks` WHERE YEAR(`date`) = $year AND MONTH(`date`) = $month AND `task_id` = '$task_id'");
        return $task_date_query;
    }

    // Feladat lekérdezése felhasználó és dátum alapján
    public function getTaskByUserAndDate($user_id, $date){
        $tasks_query = mysqli_query($this->connection, "SELECT * FROM `tasks` WHERE (`user_id`='$user_id' AND `date`='$date')");
        return $tasks_query;
    }

    // Felhasználó- és csoport feladatinak lekérdezése dátum alapján
    public function getUserAndGroupTasksForDate($user_id, $date, $group_task_ids){
        $tasks_query = mysqli_query($this->connection, "SELECT * FROM `tasks` WHERE (`user_id`='$user_id' AND `date`='$date') OR (`task_id` IN (" . implode(',', $group_task_ids) . ") AND `date`='$date')");
        return $tasks_query;
    }

    // Feladatok lekérdezése csoportfeladat azonosítók alapján
    public function getTasksByGroupTaskIds($group_task_ids){
        $tasks_query = mysqli_query($this->connection, "SELECT * FROM `tasks` WHERE (`task_id` IN (" . implode(',', $group_task_ids) . "))");
        return $tasks_query;
    }

    // Feladatok adatainak lekérdezése felhasználó és feladatazonosítók alapján 
    public function getTasksByTaskIds($user_id, $task_ids_string){
        $tasks_query = mysqli_query($this->connection, "SELECT * FROM `tasks` WHERE `user_id`='$user_id' AND `task_id` IN ($task_ids_string)");
        return $tasks_query;
    }
}

?>