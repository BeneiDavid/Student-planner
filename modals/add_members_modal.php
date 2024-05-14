
<div id="addMembersModal" class="custom-modal  delete-group-modal container">

<!-- Modal content -->
<div class="custom-modal-content">
    <span class="custom-close" id='addMembers_xButton'>&times;</span>
    <h3 id='membersModalHeader' class='word-wrap'><!-- A csoport neve dinamikusan kerül ide --></h3>

    <br>

    <form method='post' id="addMembersForm">
    <input type="search" id="searchInput" class='search-input' placeholder="Keresés..."><img src='pictures/search.svg' class='search-image' data-bs-toggle="tooltip" data-bs-placement="right" title="A mezőben kereshet név és azonosító alapján is."></img>
    <br><br>
    <div class='student-header-div'><p>Név</p><p>Azonosító</p></div>
    <div class='div-with-border search-results' id="searchResults"></div>
    <span class="error no-result-text" id='noResultText'>Nincs a keresésnek megfelelő találat.</span><br>
    <span class="info no-result-text" id='noStudents'>Nincs több hozzáadható diák.</span>
    <br><br>
    <input type='submit' name='addMembers_submit' value='Tagok hozzáadása' class='btn btn-success' id='addMembers'>
    <button type='button' class='btn btn-primary' id='addMembers_cancelButton'>Mégsem</button>
    
    </form>
</div>

</div>