<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_POST['groupAddData']))
{
  require_once __DIR__ . '/../config.php';
  require_once BASE_PATH . '/classes/user.php';
    session_start();
    $l = mysqli_connect('localhost', 'root', '', 'student_planner');
    

    $user = unserialize($_SESSION['user']);
    $user_id = $user->getId();
    $group_name = $_POST['groupName'];
    echo $user_id;
    echo $group_name;
    mysqli_query($l, "INSERT INTO `groups` SET 
    `group_id`=NULL,
    `group_teacher_id`='".$user_id."',
    `group_name`='".$group_name."'  
    "); 

    if(isset($_POST['studentIds'])) {
      $student_ids = $_POST['studentIds'];

      $last_inserted_group_id = mysqli_insert_id($l);

      foreach ($student_ids as $student_id) {
        echo $student_id;
        mysqli_query($l, "INSERT INTO `group_members` SET 
        `membership_id`=NULL,
        `group_id`='".$last_inserted_group_id."',
        `student_id`='".$student_id."'
        ");
      }
    }

    mysqli_close($l);    
}

?>

<!-- Csoport adatok modal -->
<div class='modal fade' id='groupModal'>
    <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content'>
      
            <!-- Modal header -->
            <div class='modal-header'>
              <p class='modal-title modal-header-text'>Csoport adatai</p>
              <button type='button' class='btn-close' data-bs-dismiss='modal'><span class="sr-only">Felugró ablak bezárása</span></button>
            </div>
      
            <!-- Modal body -->
            <div class='modal-body'>
                <form method='post'>
                    <div id='modalDiv'></div>
                    <span class="red label-name-error" id="groupNameError">A csoport neve nem lehet üres!<br><br></span>
                    <label for='groupName'>Csoport neve:</label> 
                    <input type='text' class='task-input task-name' id='groupName' name='groupname' maxlength="50">
                    <br><br>
                    <p>Tagok:</p>
                    <div name='members' id='membersDiv' class='members-div div-with-border'>
                    </div>
                    <br><br>
                    <input type='hidden' id='groupEditId'>
                    <div class='new-task-item  clickable no-select new-member-button' id='addNewMember'>
                        <button type='button' class='add-task-button'>
                            <img class='add-task-icon' src='pictures/plus-square.svg' alt='Tag hozzáadása'> Új tag hozzáadása
                        </button>
                    </div>
                
                </form>
            </div>
      
            <!-- Modal footer -->
            <div class='modal-footer'>
              <input type='submit' name='save_task_button' value='Mentés' class='btn btn-success' id='saveGroupButton'>
              <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Mégsem</button>
            </div>
        </div>
    </div>
</div>
