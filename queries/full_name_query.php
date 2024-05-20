<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/users.php';

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['userId'])){
    $user_id = $_POST['userId'];
    $users = new Users($l);
    $full_name_query = $users->getFullName($user_id);

    echo $users->getFullName($user_id);

    mysqli_close($l);
}

?>