<?php

    if ($_GET['email'] != '') 
    {
        $coded_email = $_GET['email'];
        $email = base64_decode($coded_email);
        $email = mysqli_real_escape_string($l, $email); 

        mysqli_query($l, "UPDATE `users` SET `reg_confirm`=1 WHERE `user_address`='".$email."'");

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