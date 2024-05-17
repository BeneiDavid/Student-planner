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
    <div id='labelBox'><p class='preview-label'>Válassza ki a címkét:</p>
    <div id='chooseLabel' class='inline-block'><div class='ellipse clickable emptylabel' id='emptyLabel'></div></div>
  </div>

    <!-- Hidden content for popover -->
    <div id='labels-popover-content' class='display-none'>
      <div id='labelPopoverContentDiv' class='label-container'>

      </div>
    </div>

    <input type='hidden' id='chosenLabelId' value=''></input>
    <br><br>
    <div id='sortByLabelDiv' class='selected-day-div sorting-table-header center-sortby-labels-div'>

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
    
    echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>

<script>
  document.title = "Student Planner - Feladatok címkék szerint";
</script>