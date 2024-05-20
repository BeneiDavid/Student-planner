<?php
$l = mysqli_connect('localhost', 'root', '', 'student_planner');
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
require_once BASE_PATH . '/classes/labels.php';

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
$labels = new Labels($l);

$label_id = $_POST['labelId'];

if(isset($_POST['showGroups'])){
    if($_POST['showGroups'] == "true"){
        $labels_query = $labels->getLabel($label_id);
    }
    else{
        $labels_query = $labels->getUserLabel($label_id, $user_id);
    }
}

if(mysqli_num_rows($labels_query) > 0){
    echo "true";
}
else{
    echo "false";
}

?>