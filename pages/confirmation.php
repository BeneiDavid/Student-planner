<?php

    if ($_GET['email'] != '') 
    {
        $coded_email = $_GET['email'];
        $email = base64_decode($coded_email);
        $email = mysqli_real_escape_string($l, $email); 

        mysqli_query($l, "UPDATE `users` SET `reg_confirm`=1 WHERE `user_address`='".$email."'");

        print '<p class="bg-success text-white"><a href="index.php?page=bejelentkezes"> Sikeres visszaigazolás, kérjük jelentkezzen be itt!</a></p>';
    }
    else
    {
        // automatikus átirányítás
        print '<meta http-equiv="refresh" content="0;url=index.php">';
    }

?>