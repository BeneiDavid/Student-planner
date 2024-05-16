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

    require 'modals/quit_group_confirm_modal.php';
    require "modals/chat_modal.php";
    require 'task_details_modal.php';
    require 'add_label_modal.php';
    require 'new_label_modal.php';
    require 'delete_task_modal.php';
    
    echo '<script type="text/javascript" src="javascript/groups.js"></script>';
    echo '<script type="text/javascript" src="javascript/chat.js"></script>';
    echo '<script type="text/javascript" src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript" src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript" src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript" src="javascript/modify_label_functions.js"></script>';

    echo "<h1 id='groupHeaderName'>Csoportok</h1><br><br>";
    echo "<div id='groupsMainDiv' class='container groups-main-div '>
            <div class='div-with-border student-groups-div align-content' id='groupsDiv'>
            </div>
            <br><br>
        </div>
       
        <div id='groupTasksDiv' class='no-display'>
        <button class='btn btn-primary' id='backToGroups'>". htmlspecialchars('<-') ." Vissza a csoportokhoz</button>
        <br><br>
        <table class=' col selected-day-div center-horizontally' id='group_task_table'>
        <tbody id='groupTasksBody' class='t_sortable'>
            <tr>
            <th colspan='1' class='progress-cell'>Feladatok</th>
            </tr>
            
           
        </tbody>
        </table>
        </div>
    ";
}
else{
        echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>

<script>
  document.title = "Student Planner - Csoportok";
</script>