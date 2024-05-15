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

    require 'task_details_modal.php';
    require 'add_label_modal.php';
    require 'new_label_modal.php';
    require 'delete_task_modal.php';
    echo '<script type="text/javascript"  src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript"  src="javascript/modify_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript"  src="javascript/folyamat.js"></script>';

    echo "<h1>Folyamat alapú rendezés</h1>";

    echo "
        <br><br>
        <div class='text-align-center' style='text-align: center;'><table class='tables_ui col selected-day-div display-inline' id='task_table'>
            <tbody id='byProgressBody' class='t_sortable'>
                <tr>
                <th colspan='1' id='labelsHeader' class='progress-cell'>Feladatok</th>
                </tr>
                <tr class='display-none'></tr>
                
                <tr id='newSortByProgressRow' class='no-drag-drop'>
                <td class='new-task-td'>
                <button class='add-task-button' id='add-task-button'>
                    <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                </button>
                </td>
                </tr>
            </tbody>
            </table>";
    echo "<table class='tables_ui  col selected-day-div display-inline' id='to_do_table'>

    <tbody class='t_sortable'  id='toDoBody'>
    
        <tr>
            <th colspan='1' id='labelsHeader' class='progress-cell'>Teendők</th>
        </tr>
        <tr class='display-none'></tr>
        <tr  class='no-drag-drop'>
            <td class='new-task-td'>

            </td>
        </tr>
    </tbody>
    </table>";
    echo "<table class='tables_ui col selected-day-div display-inline' id='in_progress_table'>

    <tbody class='t_sortable' id='inProgressBody'>
        <tr>
            <th colspan='1' id='labelsHeader' class='progress-cell'>Folyamatban</th>
        </tr>
        <tr class='display-none'></tr>
        <tr  class='no-drag-drop'>
            <td class='new-task-td'>

            </td>
        </tr>
    </tbody>
    </table>";
    echo "<table class='tables_ui col selected-day-div display-inline' id='done_table'>

    <tbody class='t_sortable'  id='doneBody'>
        <tr>
            <th colspan='1' id='labelsHeader' class='progress-cell'>Befejezve</th>
        </tr>
        <tr class='display-none'></tr>
        <tr  class='no-drag-drop'>
            <td class='new-task-td'>

            </td>
        </tr>
    </tbody>
    </table></div>";

}
else{
    print '<p class="bg-warning text-white"><a href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></p>';
}

?>


<script>
  document.title = "Student Planner - Folyamat alapú rendezés";
</script>

  
