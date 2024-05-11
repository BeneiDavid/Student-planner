<?php

require_once '../user.php';
session_start();

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

echo $user_id;


?>