<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/classes/tasks.php';

if (isset($_POST['taskAddData']))
{
    session_start();
    $l = mysqli_connect('localhost', 'root', '', 'student_planner');
    $task_id = $_POST["taskId"];
    $tasks = new Tasks($l);

    $tasks->deleteTaskLabels($task_id);
    $tasks->deleteTaskSorting($task_id);
    $tasks->deleteGroupTasks($task_id);
    $tasks->deleteTask($task_id);

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

