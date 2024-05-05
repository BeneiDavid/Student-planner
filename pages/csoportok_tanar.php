<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    require 'modals/add_members_modal.php';
    require 'modals/group_details_modal.php';
    
    echo '<script type="text/javascript"  src="javascript/teacher_groups.js"></script>';

    echo "<h1>Csoportok</h1><br><br>";
    
    echo "<div class='div-with-border groups-div' id='groupsDiv'>

        <div class='no-created-groups-div'>Ön még nem hozott létre csoportokat. Csoportok létrehozásához kattintson a lenti gombra! </div>
    
    
        </div>
    <br><br>
        <div class='new-task-item monthly-new-task div-with-border clickable no-select' id='createNewGroup'>
            <button class='add-task-button'>
                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Csoport létrehozása'>  Új csoport létrehozása
            </button>
        </div>
    
    ";
}
else{
    print '<p class="bg-warning text-white"><a href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></p>';
}

?>