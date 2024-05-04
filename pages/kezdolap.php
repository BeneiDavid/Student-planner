<?php
require_once 'user.php';
if (isset($_GET['login_success']) && $_GET['login_success'] == 1) {
    echo '<br><div class="alert alert-success" role="alert">Sikeres bejelentkezés!</div>';

  }

echo "<h1>Kezdőlap</h1>";

?>