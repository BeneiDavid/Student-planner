<?php

    if ($_GET['email'] != '') 
    {
        require_once __DIR__ . '/../config.php';
        require_once BASE_PATH . '/classes/users.php';
        $coded_email = $_GET['email'];
        $email = base64_decode($coded_email);
        $email = mysqli_real_escape_string($l, $email); 
        $users = new Users($l);

        $users->confirmAddress($email);

        echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Sikeres visszaigazolás, kérem jelentkezzen be itt!</a></div></div>';
    }
    else
    {
        // automatikus átirányítás
        print '<meta http-equiv="refresh" content="0;url=index.php">';
    }

?>


<script>
  document.title = "Student Planner - Sikeres megerősítés";
</script>