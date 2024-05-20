<?php


    require_once __DIR__ . '/../config.php';
    require_once BASE_PATH . '/classes/user.php';
    require_once BASE_PATH . '/classes/users.php';

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
    {   
        
        $password_error = "";

        $user = unserialize($_SESSION['user']);
        $user_id = $user->getId();

        if(isset($_POST['password_modify_button']))
        {
            $old_password = mysqli_real_escape_string($l, $_POST['old_password']);
            $new_password = mysqli_real_escape_string($l, $_POST['new_password']);
            $new_password_again = mysqli_real_escape_string($l, $_POST['new_password_again']);
            $l = mysqli_connect('localhost', 'root', '', 'student_planner');
            $old_hashed = hash("sha256", $old_password);
            $users = new Users($l);
            $old_password_query = $users->getPassword($user_id);
            $old_data = mysqli_fetch_array($old_password_query);


            if ($old_hashed == $old_data['user_password'])
            {
                if(empty($old_password) || empty($new_password) || empty($new_password_again)){
                    $password_error = "A régi jelszó, az új jelszó és annak megerősítése kötelező!";
                }
                else if($new_password != $new_password_again){
                    $password_error = "A jelszavak nem egyeznek meg!";
                }
                else if(strlen($new_password) < 6 || !preg_match('/[a-z]/', $new_password) ||  !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)){
                    $password_error = "A jelszó formátuma nem megfelelő! A jelszónak minimum 6 karakterből kell állnia, tartalmaznia kell kis- és nagybetűket, illetve legalább egy számot.";
                }
                else{
                    $new_hashed = hash("sha256", $new_password);
                    mysqli_query($l, "UPDATE `users` SET `user_password`='".$new_hashed."' WHERE `user_id`='".$user_id."'");
                    echo '<br><div class="content-padding"><div class="alert alert-success succesful-new-password-alert" role="alert">Új jelszavát sikeresen mentettük, mostantól ezzel tud majd belépni!</div></div>';
                }
            }
            else{
                $password_error = "A régi jelszó hibás!";
            }
          
       }


        print '<h1>Jelszó változtatás</h1>';

        print '<br><br>
            <div class="content-padding">
                <form method="post">
                    <label for="password">Régi jelszó</label><span class="error">'.$password_error.'</span><br>
                    <input type="password" id="password" name="old_password"  required class="form-control input-length">
                    <br>
                    <label for="newPassword">Új jelszó</label><img class="info-picture" src="pictures/info.png" alt="Jelszó info" data-bs-toggle="tooltip" data-bs-placement="right" title="Adjon meg egy jelszót amelyet használni szeretne. A jelszónak minimum 6 karakterből kell állnia, tartalmaznia kell kis- és nagybetűket, illetve legalább egy számot.">
                    <input type="password" id="newPassword" name="new_password"  required class="form-control input-length">
                    <br>
                    <label for="newPasswordAgain">Új jelszó újra</label>
                    <input type="password" id="newPasswordAgain" name="new_password_again"  required class="form-control input-length">
                    <br>

                    <input type="submit" name="password_modify_button" value="Új jelszó mentése" class="btn btn-success">
                </form>
            </div>';
    }
    else
    {
        echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
    }

?>

<script>
  document.title = "Student Planner - Jelszó változtatás";
</script>