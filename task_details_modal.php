<?php
require_once 'user.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo '<script type="text/javascript"  src="javascript/task_details.js"></script>';
echo '<script type="text/javascript"  src="javascript/common.js"></script>';


if (isset($_POST['taskAddData']))
{
    session_start();
    $l = mysqli_connect('localhost', 'root', '', 'student_planner');
    $task_name = mysqli_real_escape_string($l, $_POST['taskName']);
    $task_color = $_POST["colorpicker"];
    $label_ids_json = $_POST["jsonIdList"];
    $label_ids = json_decode($label_ids_json);
    $date = $_POST["date"];
    $startTime = $_POST["startTime"];
    $endTime = $_POST["endTime"];
    $enable_start_time = $_POST["enableStartTime"];
    $enable_end_time = $_POST["enableEndTime"];
    $task_description = $_POST["taskDescription"];

    $user = unserialize($_SESSION['user']);

    $user_id = $user->getId();

    if($enable_start_time == "true"){
      $enable_start_time = 1;
    }
    else{
      $enable_start_time = 0;
    }

    if($enable_end_time == "true"){
      $enable_end_time = 1;
    }
    else{
      $enable_end_time = 0;
    }

   

    
    //NOTE: egyelőre a creator id is a user !!!!
    // Feladat adatainak eltárolása
    mysqli_query($l, "INSERT INTO `tasks` SET 
        `task_id`=NULL,
        `user_id`='".$user_id."',
        `title`='".$task_name."',
        `task_description`='".$task_description."',
        `task_color` = '".$task_color."',
        `date`='".$date."',
        `start_time`='".$startTime."',
        `end_time`='".$endTime."',
        `start_time_enabled`='".$enable_start_time."',
        `end_time_enabled`='".$enable_end_time."'
        ");

    $last_inserted_task_id = mysqli_insert_id($l);
    
    foreach ($label_ids as $label_id) {
      echo $label_id;
      mysqli_query($l, "INSERT INTO `task_labels` SET 
      `task_label_id`=NULL,
      `task_id`='".$last_inserted_task_id."',
      `label_id`='".$label_id."'
      ");
    }

    $group_id = "";

    if(isset($_SESSION['group_id'])){
      if($_SESSION['group_id'] != ""){
        $group_id = $_SESSION['group_id'];
        mysqli_query($l, "INSERT INTO `group_tasks` SET 
        `group_task_id`=NULL,
        `task_id`='".$last_inserted_task_id."',
        `group_id`='".$group_id."'
        ");      
      }
    }


    // Feladat és annak címkéinek eltárolása
   

    mysqli_close($l);
    
}

?>






<div class='modal fade' id='taskModal'>
        <div class='modal-dialog modal-dialog-centered modal-lg'>
          <div class='modal-content'>
      
            <!-- Modal Header -->
            <div class='modal-header'>
              <h4 class='modal-title'>Feladat adatai<span id='groupNameSpan'></span></h4>
              <button type='button' class='btn-close' data-bs-dismiss='modal'><span class="sr-only">Felugró ablak bezárása</span></button>
            </div>
      
            <!-- Modal body -->
            <div class='modal-body'>
                <form method='post'>
                    <input type='hidden' id='existingTask'>

                    <label for='taskname'>Feladat neve:</label> 
                    <input type='text' class='task-input task-name' id='taskname' name='taskname' maxlength="50"><span class="error label-name-error" id="taskNameError">A feladat neve nem lehet üres!</span>
                    <img src="pictures/delete.svg" id="deleteTask" alt='Feladat törlése' class="delete-task-icon clickable"></img>
                    <br><br>
                    

                    <label for='colorpicker' >Feladatszín:</label> 
                    <input type='color' id='colorpicker' class='colorpicker task-input'><br><br>

                    <p>Címkék:</p>
                    <div id='added_labels'>

                    </div><br>
                    <div id='modal_div'></div>

                    <label for='date'>Dátum:</label>
                    <input type='date' class='task-input' id='date'><span class="error label-name-error" id="dateError">A dátum nem lehet üres!</span><br><br>
                
                    <label for='enableStartTime' class='sr-only'>Kezdő időpont használata</label>
                    <input type='checkbox' id='enableStartTime' > 
                    <label for='startTime'>Kezdő időpont:</label>
                    <input type='time' class='task-input' id='startTime'><span class="error label-name-error" id="startTimeError">Ha üresen szeretné hagyni a kezdő időpontot, pipálja ki a mellette lévő négyzetet!</span> <span class="error label-name-error" id="timeValueError">A befejező időpont nem lehet a kezdő időpont előtt!</span><br><br>
                
                    <label for='enableEndTime' class='sr-only'>Befejező időpont használata</label>
                    <input type='checkbox' id='enableEndTime' > 
                    <label for='endTime' >Befejező időpont:</label>
                    <input type='time' class='task-input' id='endTime'><span class="error label-name-error" id="endTimeError">Ha üresen szeretné hagyni a befejező időpontot, pipálja ki a mellette lévő négyzetet!</span>

                <br><br>

                <label for='taskDescription'>Feladat leírása</label><br>
                <textarea rows='10' class='container-fluid' id="taskDescription"  maxlength="400"></textarea>
                <br><br>
            
                </form>
            </div>
      
            <!-- Modal footer -->
            <div class='modal-footer'>
              <input type='submit' name='save_task_button' value='Mentés' class='btn btn-success' id='saveTaskButton'>
              <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Mégsem</button>
            </div>
      
          </div>
        </div>
      </div>