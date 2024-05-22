<?php

class User {
    // Properties
    private $userId;
    private $userName;
    private $fullName;
    private $userType;
    private $address;
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
    }

    // Methods

    // Felhasználó azonosító lekérdezése
    public function getId(){
        return $this->userId;
    }

    // Azonosító lekérdezése
    public function getUsername(){
        return $this->userName;
    }

    // Felhasználó típus lekérdezése
    public function getUserType(){
        return $this->userType;
    }

    // Teljes név lekérdezése
    public function getFullName(){
        return $this->fullName;
    }

    // E-mail cím lekérdezése
    public function getAddress(){
        return $this->address;
    }
}

?>