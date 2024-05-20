<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Users {
    // Properties
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection; 
    }

    public function getUserIdAndFullName($user_id){
        $teacher_query = mysqli_query($this->connection, "SELECT `user_id`, `full_name` FROM `users` WHERE `user_id`='$user_id'");
        return $teacher_query;
    }
}

?>