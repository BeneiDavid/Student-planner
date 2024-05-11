<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    require 'task_details_modal.php';
    require 'add_label_modal.php';
    require 'new_label_modal.php';
    require 'delete_task_modal.php';
    require 'choose_sortbylabel_modal.php';
    echo '<script type="text/javascript"  src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript"  src="javascript/modify_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript"  src="javascript/cimkeszerint.js"></script>';
    echo "<h1>Feladatok címkék szerint</h1>";

    echo "
    <br><br>
    <div class='center-div'>
    <div id='labelBox'><h4 class='preview-label'>Válassza ki a címkét:</h4>
    <div id='chooseLabel' class='inline-block'><div class='ellipse clickable emptylabel' id='emptyLabel'></div></div>
  </div>

    <!-- Hidden content for popover -->
    <div id='labels-popover-content' style='display: none;'>
      <div id='labelPopoverContentDiv' class='label-container'>

      </div>
    </div>

    <input type='hidden' id='chosenLabelId' value=''></input>
    <br><br>
    <div id='sortByLabelDiv' class='col selected-day-div'>

    <input type='checkbox' id='showGroupLabelsCheckbox' class='show-group-labels-checkbox' name='showGroupLabelsCheckbox'>
    <label for='showGroupLabelsCheckbox'>Csoporfeladatok címkéinek megjelenítése</label>
    
        <table>
            <thead>
                <tr>
                <th colspan='1' id='labelsHeader'></th>
                </tr>
            </thead>
            <tbody id='labelBody'>
                <tr id='newSortTaskByLabelRow'>
                <td class='new-task-td'>
                <button class='add-task-button' id='add-task-button'>
                    <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                </button>
                </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
    </div>
    
    ";
}
else{
    print '<p class="bg-warning text-white"><a href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></p>';
}

?>

