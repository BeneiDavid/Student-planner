<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    require_once __DIR__ . '/../config.php';
    require_once BASE_PATH . '/classes/user.php';
    $user = unserialize($_SESSION['user']);
    $user_type = $user->getUserType();

    if($user_type != 'student'){
        header("Location: index.php?page=kezdolap");
    exit;
    }

    require_once BASE_PATH . '/modals/quit_group_confirm_modal.php';
    require_once BASE_PATH . '/modals/chat_modal.php';
    require_once BASE_PATH . '/modals/task_details_modal.php';
    require_once BASE_PATH . '/modals/add_label_modal.php';
    require_once BASE_PATH . '/modals/new_label_modal.php';
    require_once BASE_PATH . '/modals/delete_task_modal.php';
    
    echo '<script type="text/javascript" src="javascript/groups.js"></script>';
    echo '<script type="text/javascript" src="javascript/chat.js"></script>';
    echo '<script type="text/javascript" src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript" src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript" src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript" src="javascript/modify_label_functions.js"></script>';

    echo "<h1 id='groupHeaderName'>Csoportok</h1><br><br>";
    echo "<div id='groupsMainDiv' class='groups-main-div '>
            <div class='div-with-border student-groups-div align-content' id='groupsDiv'>
            </div>
            <br><br>
        </div>
       
        <div id='groupTasksDiv' class='no-display'>
        <button class='btn btn-primary' id='backToGroups'>". htmlspecialchars('<-') ." Vissza a csoportokhoz</button>
        <br><br>
        <table class=' col selected-day-div sorting-table-header center-horizontally' id='group_task_table'>
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