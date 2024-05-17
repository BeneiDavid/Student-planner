<!-- Csoport törlés megerősítés modal -->

<div id="groupDeleteConfirmModal" class="custom-modal delete-group-modal">
    <div class="custom-modal-content">
        <span class="custom-close" id='groupDelete_xButton'>&times;</span>
        <p class='modal-header-text'>Csoport törlése</p>
        <form method='post' id="deleteTaskForm">
            <p>Biztosan törli a(z) <span id='groupToDeleteSpan'></span> csoportot?</p>
            <input type='hidden' id='groupToDeleteId'>
            <input type='submit' name='labels_save_button' value='Törlés' class='btn btn-danger' id='confirmGroupDelete'>
            <button type='button' class='btn btn-primary' id='groupDelete_cancelButton'>Mégsem</button>
        </form>
    </div>
</div>