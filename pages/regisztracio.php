<?php

require_once 'config.php';
require_once BASE_PATH . '/classes/registrationvalidator.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    header("Location: index.php?page=kezdolap");
}

?>

<h1>Regisztráció</h1>
<br><br>
<?php
    // Az elküldött űrlap feldolgozása

    if (isset($_POST['reg_button']))
    {
        $validator = new RegistrationValidator($l);
        $errors = array();

        if($validator->validateForm()){
            echo '<div class="content-padding"><div class="alert alert-success succesful-registration-alert" role="alert">Sikeres regisztráció!</div></div><br>';
        }
        else{
            $fullname = $validator->getFullName();
            $username = $validator->getUsername();
            $email = $validator->getAddress();
            $errors = $validator->getErrors();
            
            if(!empty($errors["emailsender"])){
                print("A visszaigazoló email küldése sikertelen. Kérem próbálja meg újra.");
            }
        }
    }
    ?>

    <form method="post" class='content-padding'>
        <label for="fullname">Teljes név</label><img class="info-picture" src="pictures/info.png" alt="Teljes név info" data-bs-toggle="tooltip" data-bs-placement="right" title="Adja meg a teljes nevét."><?php if(isset($errors["fullname"])) echo '<span class="error">' . $errors['fullname'] . '</span>'; ?><br> 
        <input type="text" id="fullname" name="fullname" class="form-control input-length" value="<?php echo $fullname; ?>"><br>
        <label for="username">Diák azonosító</label><img class="info-picture" src="pictures/info.png" alt="Diák azonosító info" data-bs-toggle="tooltip" data-bs-placement="right" title="Adja meg a 6 karakterből álló diák azonosítóját."><?php if(isset($errors["username"])) echo '<span class="error">' . $errors['username'] . '</span>'; ?><br>
        <input type="text" id="username" name="username" class="form-control input-length" autocomplete="username" value="<?php echo $username; ?>"><br>
        <label for="email">E-mail cím</label><img class="info-picture" src="pictures/info.png" alt="E-mail cím info" data-bs-toggle="tooltip" data-bs-placement="right" title="Adja meg az email címét, mellyel kezelni szeretné a fiókját."><?php if(isset($errors["address"])) echo '<span class="error">' . $errors['address'] . '</span>'; ?><br>
        <input type="email" id="email" name="email" class="form-control input-length" autocomplete="email" value="<?php echo $email; ?>"><br>
        <label for="password">Jelszó</label><img class="info-picture" src="pictures/info.png" alt="Jelszó info" data-bs-toggle="tooltip" data-bs-placement="right" title="Adjon meg egy jelszót amelyet használni szeretne. A jelszónak minimum 6 karakterből kell állnia, tartalmaznia kell kis- és nagybetűket, illetve legalább egy számot."><?php if(isset($errors["password"])) echo '<span class="error">' . $errors['password'] . '</span>'; ?><br>
        <input type="password" id="password" name="password" class="form-control input-length"><br>
        <label for="password2">Jelszó újra</label><br>
        <input type="password" id="password2" name="password2" class="form-control input-length"><br>
        
        <input type="submit" name="reg_button" value="Regisztráció" class="btn btn-success">
    
    </form>


<script>
  document.title = "Student Planner - Regisztráció";
</script>






