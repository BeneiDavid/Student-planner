<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['taskAddData']))
{
    session_start();
    $l = mysqli_connect('localhost', 'root', '', 'student_planner');
    $task_id = $_POST["taskId"];
    $relation_delete_query =  mysqli_query($l, "DELETE FROM `task_labels` WHERE `task_id`='$task_id'");
    $task_sorting_delete_query = mysqli_query($l, "DELETE FROM `task_sorting` WHERE `task_id`='$task_id'");
    $group_tasks_delete_query = mysqli_query($l, "DELETE FROM `group_tasks` WHERE `task_id`='$task_id'");
    $task_delete_query = mysqli_query($l, "DELETE FROM `tasks` WHERE `task_id`='$task_id'");
    mysqli_close($l);
}

?>

<!-- Feladat törlés modal -->
<div id="deleteTaskModal" class="custom-modal centered-modal container">
    <div class="custom-modal-content">
        <span class="custom-close" id='taskDelete_xButton'>&times;</span>
        <p class='modal-header-text'>Feladat törlése</p>
        <form method='post' id="deleteTaskForm">
            <p>Biztosan törli a feladatot?</p>
            <input type='submit' name='labels_save_button' value='Törlés' class='btn btn-danger' id='confirmDelete'>
            <button type='button' class='btn btn-primary' id='taskDelete_cancelButton'>Mégsem</button>
        </form>
    </div>
</div>

