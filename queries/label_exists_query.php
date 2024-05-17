<?php

$l = mysqli_connect('localhost', 'root', '', 'student_planner');
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

session_start();

if (!isset($_SESSION['user'])) {
  // If user is not logged in, return an error response
  http_response_code(403); // Forbidden
  echo json_encode(array('error' => 'User not logged in'));
  exit;
}

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

$label_id = $_POST['labelId'];

if(isset($_POST['showGroups'])){
    if($_POST['showGroups'] == "true"){
        $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id'");
    }
    else{
        $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `label_id`='$label_id' AND `user_id`='$user_id'");
    }
}


if(mysqli_num_rows($labels_query) > 0){
    echo "true";
}
else{
    echo "false";
}




?>