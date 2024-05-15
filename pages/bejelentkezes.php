

<?php
// PHP code starts here

if ($_SESSION['logged_in'] != 'yes')
{
    $email = isset($_SESSION['login_email']) ? $_SESSION['login_email'] : '';
    $error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';

    print ' <h1>Bejelentkezés</h1>
            <br><br>
                
                        <form method="post">
                            <label for="address">E-mail cím</label><span class="error">' . $error . '</span><br> 
                            <input type="address" id="address" name="address" class="form-control" value="'. $email .'">
                            <br>
                            <label for="email">Jelszó</label><br>    
                            <input type="password" name="password" class="form-control">
                            
                            <br>
                            <input type="submit" name="login_button" value="Belépés" class="btn btn-success">
                            
                            <br><br>
                            
                            <a href="index.php?page=elfelejtett_jelszo">Elfelejtett jelszó</a>
                        </form>
                    </div>';
    
    unset($_SESSION['login_email']);
    unset($_SESSION['login_error']);
}
else  // ha már beléptünk, akkor átugrunk az időpontfoglalásra
{
    print '<meta http-equiv="refresh" content="0;url=index.php?page=kezdolap&login_success=1">';
}
// PHP code ends here
?>

<script>
  document.title = "Student Planner - Bejelentkezés";
</script>