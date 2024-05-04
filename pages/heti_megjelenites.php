<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    require 'task_details_modal.php';
    require 'add_label_modal.php';
    require 'new_label_modal.php';
    require 'delete_task_modal.php';
    echo '<script type="text/javascript"  src="javascript/task_details.js"></script>';
    echo '<script type="text/javascript"  src="javascript/modify_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/add_label_functions.js"></script>';
    echo '<script type="text/javascript"  src="javascript/tasks.js"></script>';
    echo '<script type="text/javascript"  src="javascript/week.js"></script>';


    echo "<h1>Heti megjelenítés</h1>";

    echo "<br><br>
    <div class='container-fluid'>
    <div class='row'>
        <div class='col' >
            <div class='month'>      
                <ul>
                    <li class='prev clickable' id='prevMonth'>&#10094;</li>
                    <li class='next clickable' id='nextMonth'>&#10095;</li>
                    <li id='weekDate'>
                    2024.04.29. - 2024.05.05.
                    </li>
                    <input type='hidden' id='firstdayOfWeek' name='firstdayOfWeek'>
                    <input type='hidden' id='selectedWeekDate' name='selectedWeekDate'>
                </ul>
            </div>
            <div class='selected-day-div'>
            <table>
                        <thead>
                            <tr>
                            <th>Hétfő</th>
                            <th>Kedd</th>
                            <th>Szerda</th>
                            <th>Csütörtök</th>
                            <th>Péntek</th>
                            <th>Szombat</th>
                            <th>Vasárnap</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class='week-list-row'>
                                <td>
                                <ul id='mondayList'></ul>
                                </td>

                                <td>
                                <ul id='tuesdayList'></ul>
                                </td>

                                <td>
                                <ul id='wednesdayList'></ul>
                                </td>

                                <td>
                                <ul id='thursdayList'></ul>
                                </td>

                                <td>
                                <ul id='fridayList'></ul>
                                </td>

                                <td>
                                <ul id='saturdayList'></ul>
                                </td>

                                <td>
                                <ul id='sundayList'></ul>
                                </td>
                            </tr>
                            <tr>
                            
                            <td class='new-task-td'>
                            <button class='add-task-button' id='addMonday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>

                            
                            <td class='new-task-td'>
                            <button class='add-task-button' id='addTuesday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>

                            
                            <td class='new-task-td'>
                            <button class='add-task-button' id='addWednesday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>

                           
                            <td class='new-task-td'>
                            <button class='add-task-button' id='addThursday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>

                            
                            <td class='new-task-td'>
                            <button class='add-task-button' id='addFriday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>

                           
                            <td class='new-task-td'>
                            <button class='add-task-button' id='addSaturday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>

                            
                            <td class='new-task-td'>
                            <button class='add-task-button' id='addSunday'>
                                <img class='add-task-icon' src='pictures/plus-square.svg' alt='Feladat hozzáadása'>
                            </button>
                            </td>

                            </tr>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                    
</div>
            </div>
            </div>
            </div>";
            
    
    
    

}
else{
    print '<p class="bg-warning text-white"><a href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></p>';
}

?>