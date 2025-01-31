<!-- Kilépés a csoportból modal -->

<div id="groupQuitConfirmModal" class="custom-modal delete-group-modal">
    <div class="custom-modal-content">
        <span class="custom-close" id='groupQuit_xButton'>&times;</span>
        <p class='modal-header-text'>Kilépés a csoportból</p>
        <form method='post' id="deleteTaskForm">
            <p>Biztosan kilép a(z) <span id='groupQuitSpan'></span> csoportból?</p>
            <input type='hidden' id='groupQuitId'>
            <input type='submit' name='labels_save_button' value='Kilépés' class='btn btn-danger' id='confirmGroupQuit'>
            <button type='button' class='btn btn-primary' id='groupQuit_cancelButton'>Mégsem</button>
        </form>
    </div>
</div>