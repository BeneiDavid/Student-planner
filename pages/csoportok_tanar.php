<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    require_once __DIR__ . '/../config.php';
    require_once BASE_PATH . '/classes/user.php';
    $user = unserialize($_SESSION['user']);
    $user_type = $user->getUserType();
    if($user_type != 'teacher'){
        header("Location: index.php?page=kezdolap");
    }

    require_once BASE_PATH . '/modals/group_message_modal.php';
    require_once BASE_PATH . '/modals/add_members_modal.php';
    require_once BASE_PATH . '/modals/group_details_modal.php';
    require_once BASE_PATH . '/modals/group_delete_confirm_modal.php';
    require_once BASE_PATH . '/modals/task_details_modal.php';
    require_once BASE_PATH . '/modals/add_label_modal.php';
    require_once BASE_PATH . '/modals/new_label_modal.php';
    require_once BASE_PATH . '/modals/delete_task_modal.php';
    echo '<script type="text/javascript"  src="javascript/group_message_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript"  src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript"  src="javascript/modify_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/teacher_groups.js"></script>';

    echo "<div id='deleteGroupModalDiv'></div>";
    echo "<h1 id='groupHeaderName'>Csoportok</h1><br><br>";
    echo "<div id='groupsMainDiv' class='container groups-main-div'>
            <div class='div-with-border groups-div' id='groupsDiv'>

            </div>
            <br><br>
            <div class='new-task-item new-group div-with-border clickable no-select' id='createNewGroup'>
                <button type='button' class='add-task-button'>
                    <img class='add-task-icon' src='pictures/plus-square.svg' alt='Csoport létrehozása'>  Új csoport létrehozása
                </button>
            </div>
        </div>
       
        <div id='groupTasksDiv' class='no-display'>
            <button class='btn btn-primary' id='backToGroups'>". htmlspecialchars('<-') ." Vissza a csoportokhoz</button>
            <br><br>
            <table class=' col selected-day-div sorting-table-header center-horizontally' id='group_task_table'>
                <tbody id='groupTasksBody' class='t_sortable'>
                    <tr>
                        <th colspan='1' class='progress-cell'>Feladatok</th>
                    </tr>
                    <tr id='newGroupTaskRow' class='no-drag-drop'>
                        <td class='new-task-td'>
                            <button class='add-task-button' type='button' id='add-group-task-button'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>";
}
else{
    echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>

<script>
  document.title = "Student Planner - Csoportok";
</script>