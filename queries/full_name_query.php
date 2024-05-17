<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['userId'])){
    $user_id = $_POST['userId'];
    $full_name_query = mysqli_query($l, "SELECT `full_name` FROM `users` WHERE `user_id`='$user_id' LIMIT 1");

    $fetch_name = mysqli_fetch_assoc($full_name_query);    
    echo $fetch_name['full_name'];

    mysqli_close($l);
}

?>