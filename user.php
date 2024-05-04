<?php

class User {
    // Properties
    private $userId;
    private $userName;
    private $fullName;
    private $userType;
    private $pwdHash; // ez lehet hogy nem kell
    private $address;
    private $tasks;
    private $labels;
    private $groups;
    private $connection;

    // Constructor
    public function __construct($connection, $address) {
        $this->connection = $connection; 
        $this->address = $address;
        $user_query = mysqli_query($connection, "SELECT * FROM `users` WHERE `user_address`='$address'");
        if($userData = mysqli_fetch_assoc($user_query)){
            $this->userId = $userData['user_id'];
            $this->userName = $userData['username'];
            $this->userType = $userData['user_type'];
            $this->fullName = $userData['full_name'];
        }
        
        
        // Ide jön az adatok lekérdezése
        // $userId = ...
    }

    public function getId(){
        return $this->userId;
    }

    public function getUsername(){
        return $this->userName;
    }

    public function getUserType(){
        return $this->userType;
    }

    public function getHashedPassword(){ // ez lehet hogy nem kell
        return $this->pwdHash;
    }

    public function getAddress(){
        return $this->userType;
    }

    public function getTasks(){
        return $this->tasks;
    }

    public function getLabels(){
        return $this->labels;
    }

    public function deleteTask($task){

    }

    public function deleteLabel($label){

    }

    public function quitGroup(){

    }

    public function addNewTask($task){

    }

    public function addNewLabel($task){

    }
}

?>