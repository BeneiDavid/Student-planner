<?php

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}


$label_id = $_POST['labelId'];
$labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");
if(mysqli_num_rows($labels_query) > 0){
    echo "true";
}
else{
    echo "false";
}



?>