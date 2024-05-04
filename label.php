<?php

    class Label {
        // Properties
        private $labelId;
        private $labelName;
        private $labelColor;
        private $labelSymbolData;

        // Constructor
        public function __construct($name, $color, $symbolData) {
            $this->labelName = $name;
            $this->labelColor = $color;
            $this->labelSymbolData = $symbolData;
        }

        public function getName(){
            return $this->taskName;
        }

        public function getColor(){
            return $this->taskName;
        }

        public function getSymbolData(){
            return $this->taskName;
        }

        public function setLabel(){

        }

        public function updateLabel($name, $color, $symbolData){
            
        }
    }

?>