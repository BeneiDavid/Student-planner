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
    echo '<script type="text/javascript"  src="javascript/week.js"></script>';
    echo '<script type="text/javascript"  src="javascript/common.js"></script>';
    


    echo "<h1>Heti megjelenítés</h1>";

    echo "<br><br>
    <div class='container-fluid weekly-display'>
        <div class='row'>
            <div class='col' >
                <div class='month'>      
                    <ul>
                        <li class='prev clickable no-select' id='prevMonth'>&#10094;</li>
                        <li class='next clickable no-select' id='nextMonth'>&#10095;</li>
                        <li id='weekDate'>
                        </li>
                        <input type='hidden' id='firstdayOfWeek' name='firstdayOfWeek'>
                        <input type='hidden' id='selectedWeekDate' name='selectedWeekDate'>
                    </ul>
                </div>

                <div class='selected-day-div week-grid'>
                    <div class='week-grid-item'>
                        <h3 class='clickable no-select' id='chooseMondayDate'>Hétfő</h3>
                        <ul id='mondayList'></ul>
                        <div class='new-task-item'>
                            <button class='add-task-button' id='addMonday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </div>
                    </div>

                    <div class='week-grid-item'>
                        <h3 class='clickable no-select' id='chooseTuesdayDate'>Kedd</h3>
                        <ul id='tuesdayList'></ul>
                        <div class='new-task-item'>
                            <button class='add-task-button' id='addTuesday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </div>
                    </div>

                    <div class='week-grid-item'>
                        <h3 class='clickable no-select' id='chooseWednesdayDate'>Szerda</h3>
                        <ul id='wednesdayList'></ul>
                        <div class='new-task-item'>
                            <button class='add-task-button' id='addWednesday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </div>
                    </div>

                    <div class='week-grid-item'>
                        <h3 class='clickable no-select' id='chooseThursdayDate'>Csütörtök</h3>
                        <ul id='thursdayList'></ul>
                        <div class='new-task-item'>
                            <button class='add-task-button' id='addThursday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </div>
                    </div>

                    <div class='week-grid-item'>
                        <h3 class='clickable no-select' id='chooseFridayDate'>Péntek</h3>
                        <ul id='fridayList'></ul>
                        <div class='new-task-item'>
                            <button class='add-task-button' id='addFriday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </div>
                    </div>

                    <div class='week-grid-item'>
                        <h3 class='clickable no-select' id='chooseSaturdayDate'>Szombat</h3>
                        <ul id='saturdayList'></ul>
                        <div class='new-task-item'>
                            <button class='add-task-button' id='addSaturday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </div>
                    </div>


                    <div class='week-grid-item'>
                        <h3 class='clickable no-select' id='chooseSundayDate'>Vasárnap</h3>
                        <ul id='sundayList'></ul>
                        <div class='new-task-item'>
                            <button class='add-task-button' id='addSunday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>";
            
    
    
    

}
else{
    print '<p class="bg-warning text-white"><a href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></p>';
}

?>