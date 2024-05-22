<?php
    class LoginValidator {
        // Properties
        private $error;
        private $fields = array();
        private $address;
        private $connection;

        // Constructor
        public function __construct($connection) {
            $this->connection = $connection;
        }

        // Methods
        
        // Bejelentkezés form hitelesítése
        public function validateForm() {
            if($this->validateCredentials())
            {
                if($this->validateAddressConfirm())
                {
                    $this->login();
                    return true;
                } 
                else
                {
                    $this->error = "Az email címét még nem erősítette meg, kérem ellenőrizze a postafiókját!"; 
                }
            }
            else
            {
                $this->error = "Hibás e-mail cím vagy jelszó!"; 
            }
            return false;
        }

        // Email-cím és jelszó hitelesítése
        private function validateCredentials() {
            $address = mysqli_real_escape_string($this->connection, $_POST['address']);
            $this->address = $address;
            $password = mysqli_real_escape_string($this->connection, $_POST['password']);
            $hashed_password = hash('sha256', $password);

            $email_count = mysqli_num_rows(mysqli_query($this->connection, "SELECT * FROM `users` WHERE `user_address`='" . $address . "' AND `user_password`='" . $hashed_password . "'"));
            
            if ($email_count == 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        // Email-cím megerősítésének hitelesítése
        private function validateAddressConfirm() {
            $confirm_count = mysqli_num_rows(mysqli_query($this->connection, "SELECT * FROM `users` WHERE `user_address`='" . $this->address . "' AND `reg_confirm`=1"));

            if ($confirm_count == 1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        // Bejelentkezés
        private function login() {
            $user_data = mysqli_fetch_array(mysqli_query($this->connection, "SELECT * FROM `users` WHERE `user_address`='" . $this->address . "'"));
            $_SESSION['logged_in'] = 'yes';
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['user_type'] = $user_data['user_type'];
            $_SESSION['full_name'] = $user_data['full_name'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['user_address'] = $user_data['user_address'];
        }

        // Hiba lekérdezése
        public function getError(){
            return $this->error;
        }

        // Email-cím lekérdezése
        public function getAddress(){
            return $this->address;
        }
    }
?>