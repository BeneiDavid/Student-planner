<?php


?>



<div id="addMembersModal" class="custom-modal delete-modal container">

<!-- Modal content -->
<div class="custom-modal-content">
    <span class="custom-close" id='taskDelete_xButton'>&times;</span>
    <h3 id='membersModalHeader'><!-- A csoport neve dinamikusan kerül ide --></h3>

    <br>

    <form method='post' id="addMembersForm">
    <input type="text" id="searchInput" class='search-input' placeholder="Keressen itt..."><img src='pictures/search.svg' class='search-image'></img>
        <br><br>
        <div class='div-with-border' id="searchResults"></div>
        <br><br>
    <input type='submit' name='labels_save_button' value='Tagok hozzáadása' class='btn btn-success' id='confirmDelete'>
    <button type='button' class='btn btn-primary' id='taskDelete_cancelButton'>Mégsem</button>

    </form>
</div>

</div>