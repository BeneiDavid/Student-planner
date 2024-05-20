

<?php

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 'yes')
{
    $email = isset($_SESSION['login_email']) ? $_SESSION['login_email'] : '';
    $error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';

    print ' <h1>Bejelentkezés</h1>
            <br><br>
                
                        <form method="post" class="content-padding">
                            <label for="address">E-mail cím</label><span class="error">' . $error . '</span><br> 
                            <input type="address" id="address" name="address" autocomplete="email" class="form-control input-length" value="'. $email .'">
                            <br>
                            <label for="password">Jelszó</label><br>    
                            <input type="password" name="password" id="password" class="form-control input-length">
                            
                            <br>
                            <input type="submit" name="login_button" value="Belépés" class="btn btn-success">
                            
                            <br><br>
                            
                            <a href="index.php?page=elfelejtett_jelszo">Elfelejtett jelszó</a>
                        </form>
                    </div>';
    
    unset($_SESSION['login_email']);
    unset($_SESSION['login_error']);
}
else
{
    print '<meta http-equiv="refresh" content="0;url=index.php?page=kezdolap&login_success=1">';
}

?>

<script>
  document.title = "Student Planner - Bejelentkezés";
</script>