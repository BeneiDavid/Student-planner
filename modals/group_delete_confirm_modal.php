<div id="groupDeleteConfirmModal" class="custom-modal delete-group-modal">

<!-- Modal content -->
<div class="custom-modal-content">
    <span class="custom-close" id='groupDelete_xButton'>&times;</span>
    <h3>Csoport törlése</h3>
    <form method='post' id="deleteTaskForm">
        <p>Biztosan törli a(z) <span id='groupToDeleteSpan'></span> csoportot?</p>
    <input type='hidden' id='groupToDeleteId'>
    <input type='submit' name='labels_save_button' value='Törlés' class='btn btn-danger' id='confirmGroupQuit'>
    <button type='button' class='btn btn-primary' id='groupDelete_cancelButton'>Mégsem</button>

    </form>
</div>

</div>