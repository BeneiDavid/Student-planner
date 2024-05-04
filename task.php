<?php

    class Task {
        // Properties
        private $taskId;
        private $taskName;
        private $taskColor;
        private $taskDescription;
        private $labels = array();
        private $date;
        private $creatorId;

        // Constructor
        public function __construct($name, $color, $description, $labels, $date) {
            $this->taskName = $name;
            $this->taskColor = $color;
            $this->taskDescription = $description;
            $this->labels = $labels;
            $this->date = $date;
        }

        public function getName(){
            return $this->taskName;
        }
        public function getColor(){
            return $this->taskName;
        }
        public function getDescription(){
            return $this->taskName;
        }
        public function getLabels(){
            return $this->taskName;
        }
        public function getDate(){
            return $this->taskName;
        }
        public function getCreator(){
            return $this->taskName;
        }

        public function setTask(){

        }

        public function deleteTask(){

        }

        public function updateTask($name, $color, $description, $labels, $date){

        }

        public function addLabel($label){

        }

        public function removeLabel($label){
            
        }
    }

?>