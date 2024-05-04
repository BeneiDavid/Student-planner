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
    // If user is not logged in, return an error response
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User not logged in'));
    exit;
}


  $user = unserialize($_SESSION['user']);
  $user_id = $user->getId();
  $labels_query = mysqli_query($l, "SELECT * FROM `labels` WHERE `user_id`='$user_id'");

  if (!$labels_query) {
    // If query fails, return an error response
    http_response_code(500); // Internal Server Error
    echo json_encode(array('error' => 'Database query failed'));
    exit;
}


  $data = array();
  while ($row = mysqli_fetch_assoc($labels_query)) {
    $data[] = $row;
  }

  $jsonData = json_encode($data);

  mysqli_close($l);

  // Send JSON response
  header('Content-Type: application/json');
  echo $jsonData;

  // Close connection
  
?>


