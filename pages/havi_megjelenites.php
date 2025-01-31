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

    require_once BASE_PATH . '/modals/task_details_modal.php';
    require_once BASE_PATH . '/modals/add_label_modal.php';
    require_once BASE_PATH . '/modals/new_label_modal.php';
    require_once BASE_PATH . '/modals/delete_task_modal.php';
    echo '<script type="text/javascript"  src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript"  src="javascript/modify_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript"  src="javascript/month.js"></script>';
    echo '<script type="text/javascript"  src="javascript/common.js"></script>';
    echo "<h1>Havi megjelenítés</h1><br><br>";

    echo "<div class='div-padding-bottom content-padding'>
            <div class='month'>      
                <ul>
                    <li class='prev clickable no-select' id='prevMonth'>&#10094;</li>
                    <li class='next clickable no-select' id='nextMonth'>&#10095;</li>
                    <li id='monthAndYear'>
                    
                    </li>
                </ul>
                <input type='hidden' id='selectedMonthDate' name='selectedMonthDate'>
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
    
            <div class='days' id='calendarDays'></div>
        </div>
    
        <div class='new-task-item monthly-new-task div-with-border clickable no-select' id='addNewTaskMonthly'>
            <button class='add-task-button'>
                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>  Új feladat létrehozása
            </button>
        </div>";
}
else{
    echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>

<script>
  document.title = "Student Planner - Havi megjelenítés";
</script>
