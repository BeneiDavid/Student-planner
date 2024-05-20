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

    public function saveLabel($user_id, $label_name, $label_color, $label_symbol){
        mysqli_query($this->connection, "INSERT INTO `labels` SET 
      `label_id`=NULL,
      `user_id`='".$user_id."',
      `label_name`='".$label_name."',
      `label_color` = '".$label_color."',
      `label_symbol`='".$label_symbol."'
      ");
    }

    public function deleteLabel($label_id){
        mysqli_query($this->connection, "DELETE FROM `labels` WHERE `label_id`='$label_id'");
    }

    public function updateLabel($label_id, $label_name, $label_color, $label_symbol){
         mysqli_query($this->connection, "UPDATE `labels` SET `label_name`='$label_name', `label_color`='$label_color', `label_symbol`='$label_symbol' WHERE `label_id`='$label_id'");
    }

    public function deleteTaskLabelRelation($label_id){
        mysqli_query($this->connection, "DELETE FROM `task_labels` WHERE `label_id`='$label_id'");
    }

    public function getLabel($label_id){
        $labels_query = mysqli_query($this->connection, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");
        return $labels_query;
    }

    public function getUserLabel($label_id, $user_id){
        $labels_query = mysqli_query($this->connection, "SELECT * FROM `labels` WHERE `label_id`='$label_id' AND `user_id`='$user_id'");
        return $labels_query;
    }

    
    
}

?>