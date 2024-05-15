<?php
require_once 'user.php';
if (isset($_GET['login_success']) && $_GET['login_success'] == 1) {
    echo '<br><div class="content-padding"><div class="alert alert-success succesful-login-alert" role="alert">Sikeres bejelentkezés!</div></div>';

  }
  
echo "<h1>Kezdőlap</h1>";

?>


<script>
  document.title = "Student Planner - Kezdőlap";
</script>