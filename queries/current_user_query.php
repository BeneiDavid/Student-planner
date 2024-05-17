<?php
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/user.php';
session_start();

$user = unserialize($_SESSION['user']);
$user_id = $user->getId();

echo $user_id;

?>