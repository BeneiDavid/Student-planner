<?php

require 'emailsender.php';

class RegistrationValidator {
  // Properties
  private $errors = array();
  private $fields = array();
  private $connection;

  // Constructor
  public function __construct($connection) {
      $this->connection = $connection;
  }

  // Methods

  // Regisztrációs form hitelesítése
  public function validateForm() {
    
    $isNameValid = $this->validateName();
    $isIdValid = $this->validateId();
    $isAddressValid = $this->validateAddress();
    $isPasswordValid = $this->validatePassword();
    if($isNameValid && $isIdValid && $isAddressValid && $isPasswordValid){
        $sender = new EmailSender();
        $message = ' Kedves '.$this->fields["fullname"].'!
                    <br><br>
                    Köszönjük, hogy regisztrált!<br>
                    A regisztráció megerősítéséhez kérem kattintson a következő linkre:
                    <a href="http://localhost/Szakdolgozat/index.php?page=confirmation&email='.base64_encode($this->fields["address"]).'">http://localhost/Szakdolgozat.php?page=confirmation&email='.base64_encode($this->fields["address"]).'</a>
                  
                    <br><br>                    
                    Üdvözlettel: <br>
                    Student planner';

        $response = $sender->Send($this->fields["address"], "Regisztráció", $message);

        if($response == 'success'){
            $this->saveRegistration();
            return true;
        }
        else{
            $this->errors["emailsender"] = $response;
            return false;
        } 
    }

    return false;
  }

  // Név hitelesítése
  private function validateName() {
    $fullname = mysqli_real_escape_string($this->connection, $_POST['fullname']);
    $this->fields["fullname"] = $fullname;

    if(empty($fullname)){
        $this->errors["fullname"] = "A teljes név megadása kötelező!";
        return false;
    }

    return true;
  }

  // Azonosító hitelesítése
  private function validateId() {
    $username = mysqli_real_escape_string($this->connection, $_POST['username']);
    $this->fields["username"] = $username;
    
    if(empty($username)){
        $this->errors["username"] = "A diák azonosító megadása kötelező!";
        return false;
    }
    else if(strlen($username) != 6){
        $this->errors["username"] = "A diák azonosítónak 6 karakterből kell állnia!";
        return false;
    }
    else if(!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
      $this->errors["username"] = "A diák azonosító csak betűket és számokat tartalmazhat!";
      return false;
    }
    
    $usernameCount = mysqli_num_rows(mysqli_query($this->connection, "SELECT * FROM `users` WHERE `username`='$username'"));

    if($usernameCount != 0){
        $this->errors["username"] = "A megadott diák azonosítóval már regisztráltak! Kérem jelentkezzen be, vagy vegye fel a kapcsolatot a rendszergazdával!";
        return false;
    }

    return true;
  }

  // E-mail cím hitelesítése
  private function validateAddress() {
    $address = mysqli_real_escape_string($this->connection, $_POST['email']);
    $this->fields["address"] = $address;

    if(empty($address)){
        $this->errors["address"] = "Az e-mail cím megadása kötelező!";
        return false;
    }
    else if(!filter_var($address, FILTER_VALIDATE_EMAIL)){
        $this->errors["address"] = "Az e-mail cím formátuma hibás!";
        return false;
    }

    $emailCount = mysqli_num_rows(mysqli_query($this->connection, "SELECT * FROM `users` WHERE `user_address`='$address'"));
    
    if($emailCount != 0){
        $this->errors["address"] = "A megadott e-mail címmel már regisztráltak! Kérem jelentkezzen be, vagy adjon meg egy másik e-mail címet!";
        return false;
    }

    return true;
  }

  // Jelszó hitelesítése
  private function validatePassword() {
    $password = mysqli_real_escape_string($this->connection, $_POST['password']);
    $password2 = mysqli_real_escape_string($this->connection, $_POST['password2']);
    $this->fields["password"] = $password;

    if(empty($password) || empty($password2)){
        $this->errors["password"] = "A jelszó és annak megerősítése kötelező!";
        return false;
    }
    else if($password != $password2){
        $this->errors["password"] = "A jelszavak nem egyeznek meg!";
        return false;
    }
    else if(strlen($password) < 6 || !preg_match('/[a-z]/', $password) ||  !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)){
        $this->errors["password"] = "A jelszó formátuma nem megfelelő! A jelszónak minimum 6 karakterből kell állnia, tartalmaznia kell kis- és nagybetűket, illetve legalább egy számot.";
        return false;
    }


    return true;
  }

  // Regisztráció mentése
  private function saveRegistration(){
    $hashed_password = hash('sha256',$this->fields["password"]);

        if (!mysqli_query($this->connection, "INSERT INTO `users` SET 
        `user_id`=NULL,
        `user_type`='student',
        `full_name`='".$this->fields["fullname"]."',
        `username` = '".strtolower($this->fields["username"])."',
        `user_address`='".$this->fields["address"]."',
        `user_password`='".$hashed_password."',
        `reg_date`='".date('Y-m-d H:i:s')."',
        `reg_confirm`= 0  ")) {
          echo("Error description: " . mysqli_error($this->connection));
        }
  }

  // Teljes név lekérdezése
  public function getFullName(){
    return $this->fields["fullname"];
  }

  // Azonosító lekérdezése
  public function getUsername(){
    return $this->fields["username"];
  }

  // E-mail cím lekérdezése
  public function getAddress(){
    return $this->fields["address"];
  }

  // Hibák lekérdezése
  public function getErrors(){
    return $this->errors;
  }
}

?>