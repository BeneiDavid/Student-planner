<?php

class Chat {
    // Properties

    private $chatId;
    private $messageTimes;
    private $messages;
    private $areMessagesSentByUser;
    private $thisUserId;
    private $otherUserId;

    // Constructor
    public function __construct($thisUserId, $otherUserId) {
        $this->thisUsedId = $thisUserId;
        $this->otherUserId = $otherUserId;
    }

    public function sendMessage($message){

    }

    
    public function refresh(){

    }

    
    public function getLogs(){

    }

}

?>