<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
    {
        header("Location: index.php?page=kezdolap");
    }


    if(isset($_POST['new_password_button']))
    {
        $email = mysqli_real_escape_string($l, $_POST['email']);

        $user_query = mysqli_query($l,"SELECT * FROM `users` WHERE `user_address`='".$email."'");

        if (mysqli_num_rows($user_query) == 1)
        {
            $letters = ["A", "B", "C", "D", "E", "F"];
            $first_letter = $letters[mt_rand(0, 5)];
            $second_letter = $letters[mt_rand(0, 5)];
            if (mt_rand(0, 1) == 0) {
                $first_letter = strtolower($first_letter);
            } else {
                $second_letter = strtolower($second_letter);
            }
            $new_password = $first_letter . $second_letter . mt_rand(10000,99999);
            $new_hashed_password = hash('sha256', $new_password);
            mysqli_query($l,"UPDATE `users` SET `user_password`='".$new_hashed_password."' WHERE `user_address`='".$email."'");
           
            $user_data = $user_query->fetch_assoc();
            
            require ('emailsender.php');

            $sender = new EmailSender();
            $message = ' Kedves '.$user_data['full_name'].'!
                        <br><br>
                        A kérésének megfelelően új jelszót generáltunk Önnek!<br>
                        Mostantól a következő jelszóval fog tudni belépni az oldalra, ahol megváltoztathatja ezt a jelszót!<br>

                        <p style="color: red">Új jelszava: '.$new_password.'</p> 
                        Itt tud belépni: <a href="http://localhost/Szakdolgozat/index.php?page=bejelentkezes">http://localhost/Szakdolgozat/index.php?page=bejelentkezes</a>
                        
                        <br><br>                    
                        Üdvözlettel: <br>
                        Student planner';
    
            $response = $sender->Send($user_data['user_address'], "Új jelszó", $message);

            if($response != "success"){
                echo $response;
            }
        }

        echo '<br><div class="content-padding"><div class="alert alert-success succesful-new-password-alert" role="alert">Új jelszavát elküldtük emailben, kérjük ellenőrizze postafiókját!</div></div>';
        
    }
    print ' <h1>Elfelejtett jelszó</h1>';

    print '<br><br>
    <div class="content-padding">  
            <form method="post">
                <label for="email">E-mail cím</label>
                <input class="form-control input-length" type="email" name="email" id="email"  autocomplete="email" required>
                <br>
                <input type="submit" name="new_password_button" value="Új jelszó igénylése" class="btn btn-success">                                                                           
            </form>
    </div>';

?>