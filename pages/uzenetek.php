<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    require_once 'user.php';
    $user = unserialize($_SESSION['user']);
    $user_type = $user->getUserType();
    if($user_type != 'student'){
        header("Location: index.php?page=kezdolap");
    exit;
    }
    
    require "modals/chat_modal.php";
    echo '<script type="text/javascript"  src="javascript/messages_student.js"></script>';
    echo '<script type="text/javascript"  src="javascript/chat.js"></script>';
    echo '<script type="text/javascript"  src="javascript/common.js"></script>';

    echo "<h1>Üzenetek</h1><br><br>";

    echo"<div class='teachers-heading' ><p>Oktató</p><p>Csoportok</p></div>";
    echo"<div id='teachersDiv' class='group-members-div'></div>";

}
else{
    echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>

<script>
  document.title = "Student Planner - Üzenetek";
</script>