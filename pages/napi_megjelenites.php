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
    echo '<script type="text/javascript"  src="javascript/week.js"></script>';
    echo '<script type="text/javascript"  src="javascript/calendar.js"></script>';
    

    echo "<h1>Napi megjelenítés</h1><br>";

    echo "
        <div class='container-fluid'>
            <div class='row'>
                <div class='col col-sm-12 col-12 col-md-12  col-lg-5' >
                    <div class='month'>      
                        <ul>
                            <li class='prev clickable no-select' id='prevMonth'>&#10094;</li>
                            <li class='next clickable no-select' id='nextMonth'>&#10095;</li>
                            <li id='monthAndYear' class='month-and-year'></li>
                        </ul>
                            <input type='hidden' id='selectedDate' name='selectedDate'>
                            <input type='hidden' id='calendarYearAndMonth' name='calendarYearAndMonth'>
                    </div>
                
                <ul class='weekdays'>
                    <li>H</li>
                    <li>K</li>
                    <li>Sze</li>
                    <li>Cs</li>
                    <li>P</li>
                    <li>Szo</li>
                    <li>V</li>
                </ul>
                    <!-- <ul class='days' id='calendarDays'></ul> -->
                    <div class='days' id='calendarDays'></div>
                </div>";
                    

                echo "
                <div class='col selected-day-div  col-12 col-sm-12 col-md-12  col-lg-7 mt-4 mt-lg-0'>
                    <table>
                        <thead>
                            <tr>
                            <th colspan='2' id='selectedDay'></th>
                            </tr>
                        </thead>
                        <tbody id='taskBody'>
                            <tr id='newTaskRow'>
                            <td class='first-col'></td>
                            <td class='new-task-td'>
                            <button class='add-task-button' id='add-task-button'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>";

}

else
{
    echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>

<script>
  document.title = "Student Planner - Napi megjelenítés";
</script>