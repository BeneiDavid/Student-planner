<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once 'user.php';

    $l = mysqli_connect('localhost', 'root', '', 'student_planner');

    if (!$l) {
      die("Connection failed: " . mysqli_connect_error());
    }
  
    session_start();
  
    if (!isset($_SESSION['user'])) {
      http_response_code(403);
      echo json_encode(array('error' => 'User not logged in'));
      exit;
    }
  
    $user = unserialize($_SESSION['user']);
    $user_id = $user->getId();

    $first_label_query = mysqli_query($l, "SELECT `label_id` FROM `labels` WHERE `user_id`='$user_id' LIMIT 1");

    $first_label_id = "";
   
    // Check if the query was successful
    if ($first_label_query) {
        
        // Fetch the result as an associative array
        $first_label_data = mysqli_fetch_assoc($first_label_query);
       
        // Check if data was fetched
        if ($first_label_data) {
            // Access the label_id column from the fetched data
            $first_label_id = $first_label_data['label_id'];
        }
    }

    echo $first_label_id;







?>