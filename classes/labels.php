<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
class Labels {
    // Properties
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection; 
    }

    // Címke mentése
    public function saveLabel($user_id, $label_name, $label_color, $label_symbol){
        mysqli_query($this->connection, "INSERT INTO `labels` SET 
      `label_id`=NULL,
      `user_id`='".$user_id."',
      `label_name`='".$label_name."',
      `label_color` = '".$label_color."',
      `label_symbol`='".$label_symbol."'
      ");
    }

    // Címke törlése
    public function deleteLabel($label_id){
        mysqli_query($this->connection, "DELETE FROM `labels` WHERE `label_id`='$label_id'");
    }

    // Címke frissítése
    public function updateLabel($label_id, $label_name, $label_color, $label_symbol){
         mysqli_query($this->connection, "UPDATE `labels` SET `label_name`='$label_name', `label_color`='$label_color', `label_symbol`='$label_symbol' WHERE `label_id`='$label_id'");
    }

    // Címke hozzárendelés törlése
    public function deleteTaskLabelRelation($label_id){
        mysqli_query($this->connection, "DELETE FROM `task_labels` WHERE `label_id`='$label_id'");
    }

    // Címke adatainak lekérdezése
    public function getLabel($label_id){
        $labels_query = mysqli_query($this->connection, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");
        return $labels_query;
    }

    // Felhasználó címkéinek lekérdezése címke azonosító alapján
    public function getUserLabel($label_id, $user_id){
        $labels_query = mysqli_query($this->connection, "SELECT * FROM `labels` WHERE `label_id`='$label_id' AND `user_id`='$user_id'");
        return $labels_query;
    }

    // Felhasználó címkéinek lekérdezése
    public function getUserLabels($user_id){
        $labels_query = mysqli_query($this->connection, "SELECT * FROM `labels` WHERE `user_id`='$user_id'");
        return $labels_query;
    }

    // Csoportfeladatok címkéienk lekérdezése
    public function getGroupLabelsForStudent($label_id, $user_id){
        $groups_labels_query =  mysqli_query($this->connection, "SELECT * FROM `labels` WHERE `label_id`='$label_id' AND `user_id` != '$user_id'");
        return $groups_labels_query;
    }
    
    // Felhasználó első címkeazonosítójának lekérdezése
    public function getFirstLabelId($user_id){
        $first_label_query = mysqli_query($this->connection, "SELECT `label_id` FROM `labels` WHERE `user_id`='$user_id' LIMIT 1");
        return $first_label_query;
    }

    // Címke azonosítók lekérdezése feladat azonosító alapján
    public function getLabelIdsForTask($task_id){
        $groups_task_labels_query =  mysqli_query($this->connection, "SELECT label_id FROM `task_labels` WHERE `task_id`='$task_id'");
        return $groups_task_labels_query;
    }
}

?>