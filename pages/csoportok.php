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
    

    echo "<h1 id='groupHeaderName'>Csoportok</h1><br><br>";
    echo "<div id='groupsMainDiv' class='container groups-main-div'>
            <div class='div-with-border groups-div' id='groupsDiv'>

            <div class='no-created-groups-div'>Ön még nem nincs benne egy csoportban sem.</div>
        
        
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
            
            <tr id='newGroupTaskRow' class='no-drag-drop'>
            <td class='new-task-td'>
            <button class='add-task-button' id='add-group-task-button'>
                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
            </button>
            </td>
            </tr>
        </tbody>
        </table>
        </div>
    ";
}
else{
    print '<p class="bg-warning text-white"><a href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></p>';
}

?>