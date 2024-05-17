<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    require_once 'config.php';
    require_once BASE_PATH . '/classes/user.php';
    $user = unserialize($_SESSION['user']);
    $user_type = $user->getUserType();
    if($user_type != 'student'){
        header("Location: index.php?page=kezdolap");
    exit;
    }

    require_once BASE_PATH . '/modals/task_details_modal.php';
    require_once BASE_PATH . '/modals/add_label_modal.php';
    require_once BASE_PATH . '/modals/new_label_modal.php';
    require_once BASE_PATH . '/modals/delete_task_modal.php';
    echo '<script type="text/javascript"  src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript"  src="javascript/modify_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript"  src="javascript/eisenhover.js"></script>';

    echo "<h1>Eisenhover mátrix</h1>";

    echo "
        <br><br>

        <table class='tables_ui col selected-day-div sorting-table-header center-horizontally' id='task_table'>
            <tbody id='eisenhoverBody' class='t_sortable'>
                <tr>
                <th colspan='1' id='labelsHeader' class='progress-cell'>Feladatok</th>
                </tr>
                <tr class='display-none'></tr>
                
                <tr id='newEisenhoverRow' class='no-drag-drop'>
                <td class='new-task-td'>
                <button class='add-task-button' id='add-task-button'>
                    <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                </button>
                </td>
                </tr>
            </tbody>
        </table>";

    echo "<div class='grid-container padding-bottom'>
                <div class='grid-item'>
                    <table class='col selected-day-div sorting-table-header' id='urgent_important'>
                        <tbody id='urgImp' class='t_sortable'>
                            <tr>
                                <th colspan='1' id='labelsHeader'>Fontos - Sürgős</th>
                            </tr>
                            <tr class='display-none'></tr>
                            <tr  class='no-drag-drop'>
                                <td class='new-task-td'></td>
                            </tr>
                        </tbody>
                    </table>
                </div>";

    echo "      <div class='grid-item'>
                    <table class='col selected-day-div sorting-table-header' id='urgent_notimportant'>
                    <tbody id='urgNotImp' class='t_sortable'>
                        <tr>
                            <th colspan='1' id='labelsHeader'>Fontos - Nem sürgős</th>
                        </tr>
                        <tr class='display-none'></tr>
                        <tr  class='no-drag-drop'>
                            <td class='new-task-td'></td>
                        </tr>
                    </tbody>
                    </table>
                </div>";

    echo "      <div class='grid-item'>
                    <table class='col selected-day-div sorting-table-header' id='noturgent_important'>
                        <tbody id='notUrgImp' class='t_sortable'>
                            <tr>
                                <th colspan='1' id='labelsHeader'>Nem fontos - Sürgős</th>
                            </tr>
                            <tr class='display-none'></tr>
                            <tr  class='no-drag-drop'>
                                <td class='new-task-td'></td>
                            </tr>
                        </tbody>
                    </table>
                </div>";

    echo "      <div class='grid-item'>
                    <table class='col selected-day-div sorting-table-header' id='noturgent_notimportant'>

                        <tbody id='notUrgNotImp' class='t_sortable'>
                            <tr>
                                <th colspan='1' id='labelsHeader'>Nem fontos - Nem sürgős</th>
                            </tr>
                            <tr class='display-none'></tr>
                            <tr  class='no-drag-drop'>
                                <td class='new-task-td'>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>";

}
else{
    echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>


<script>
  document.title = "Student Planner - Eisenhover mátrix";
</script>