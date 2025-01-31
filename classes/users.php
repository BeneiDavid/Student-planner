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

    // Felhasználó azonosító és név lekérdezése
    public function getUserIdAndFullName($user_id){
        $teacher_query = mysqli_query($this->connection, "SELECT `user_id`, `full_name` FROM `users` WHERE `user_id`='$user_id'");
        return $teacher_query;
    }

    // Megerősített regisztrációjú diák adatainak lekérdezése
    public function getStudentData($student_id){
        $students_query = mysqli_query($this->connection, "SELECT user_id, full_name, username FROM `users` WHERE `user_id`=$student_id AND `reg_confirm`='1'");
        return $students_query;
    }

    // Email-cím megerősítése
    public function confirmAddress($user_address){
        mysqli_query($this->connection, "UPDATE `users` SET `reg_confirm`=1 WHERE `user_address`='".$user_address."'");
    }

    // Felhasználó adatainak lekérdezése email-cím alapján
    public function getUserByAddress($user_address){
        $user_query = mysqli_query($this->connection,"SELECT * FROM `users` WHERE `user_address`='".$user_address."'");
        return $user_query;
    }

    // Új jelszó beállítása
    public function setNewPassword($user_address, $user_password){
        mysqli_query($this->connection,"UPDATE `users` SET `user_password`='".$user_password."' WHERE `user_address`='".$user_address."'");
    }

     // Jelszó lekérdezése
    public function getPassword($user_id){
        $password_query = mysqli_query($this->connection, "SELECT `user_password` FROM `users` WHERE `user_id`='".$user_id."'");
        return $password_query;
    }

    // Teljes név lekérdezése
    public function getFullName($user_id){
        $full_name_query = mysqli_query($this->connection, "SELECT `full_name` FROM `users` WHERE `user_id`='$user_id' LIMIT 1");
        $fetch_name = mysqli_fetch_assoc($full_name_query);    
        return $fetch_name['full_name'];
    }

    // Összes megerősített regisztrációjú hallgató lekérdezése
    public function getAllConfirmedStudents(){
        $students_query = mysqli_query($this->connection, "SELECT user_id, full_name, username FROM `users` WHERE `user_type`='student' AND `reg_confirm`='1'");
        return $students_query;
    }
}

?>