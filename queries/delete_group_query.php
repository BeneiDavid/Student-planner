<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$l = mysqli_connect('localhost', 'root', '', 'student_planner');

if (!$l) {
  die("Connection failed: " . mysqli_connect_error());
}

$group_id = $_POST['groupId'];
$group_query = mysqli_query($l, "DELETE FROM `groups` WHERE `group_id`='$group_id'");
$members_query = mysqli_query($l, "DELETE FROM `group_members` WHERE `group_id`='$group_id'");


echo "success";

mysqli_close($l);









?>