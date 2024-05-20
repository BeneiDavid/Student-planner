<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Messages {
    // Properties
    private $connection;

    // Constructor
    public function __construct($connection) {
        $this->connection = $connection; 
    }

    // Üzenet küldése
    public function sendMessage($sender_id, $receiver_id, $message){
        $currentDate = date('Y-m-d H:i:s'); 

        $message_send_query = mysqli_query($this->connection, "INSERT INTO `messages` SET
        `message_id`=NULL,
        `sender_id`='".$sender_id."',
        `receiver_id` = '".$receiver_id."',
        `message_text`='".$message."',
        `message_time`='".$currentDate."',
        `seen_by_receiver`= 0 
        ");
    }

    // Felhasználó által látott üzenetek frissítése
    public function updateMessagesSeenByUser($current_user_id, $other_user_id){
        $update_seen_messages_query = mysqli_query($this->connection, "UPDATE `messages` SET `seen_by_receiver`='1' WHERE `sender_id`='$other_user_id' AND `receiver_id`='$current_user_id'");
    }

    // Üzenetváltás lekérdezése
    public function getMessages($current_user_id, $other_user_id){
        $get_message_query = mysqli_query($this->connection, "SELECT * FROM `messages` WHERE `sender_id`='$current_user_id' AND `receiver_id`='$other_user_id' OR 
        `sender_id`='$other_user_id' AND `receiver_id`='$current_user_id'");

        $messages = [];
        
        while($row = mysqli_fetch_assoc($get_message_query)){
            $messages[] = $row;
        }

        return $messages;
    }

    // Látott-e már minden üzenetet a felhasználó egy másik felhasználótól
    public function areAllMessagesSeenByUser($current_user_id, $other_user_id){
        $messages_seen_query = mysqli_query($this->connection, "SELECT `seen_by_receiver` FROM `messages` WHERE `sender_id`='$other_user_id' AND `receiver_id`='$current_user_id' AND `seen_by_receiver`='0'");
       
        if (mysqli_num_rows($messages_seen_query) > 0) {
            return false;
        }
        else{
            return true;
        }   
    }
}

?>