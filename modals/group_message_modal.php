<div class='modal fade' id='groupMessageModal'>
    <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content'>
      
            <!-- Modal Header -->
            <div class='modal-header'>
              <h4 class='modal-title'>Csoportüzenet - <span id='groupMessageGroupNameSpan'></span></h4>
              <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
            </div>
      
            <!-- Modal body -->
            <div class='modal-body'>
                <form method='post' id="addMembersForm">
                    <input type="search" id="messageSearchInput" class='search-input' placeholder="Keresés..."><img src='pictures/search.svg' class='search-image' data-bs-toggle="tooltip" data-bs-placement="right" title="A mezőben kereshet név és azonosító alapján is."></img>
                    <br><br>
                    <div class='student-header-div'><p>Név</p><p>Azonosító</p>
                    <img class='info-picture' src='pictures/info.png' data-bs-toggle="tooltip" data-bs-placement="right" title="Jelölje ki a hallgatókat akiknek az üzenetet szeretné küldeni, vagy jelölje be az 'Üzenet küldése az egész csoportnak' opciót!"></img>
                </div>
                    <div class='div-with-border search-results' id="searchStudentResults"></div>
                    <span class="error no-result-text" id='noSearchResultText'>Nincs a keresésnek megfelelő találat.</span><br>
                    <span class="info no-result-text" id='noRegisteredStudents'>A csoportnak még nincsenek tagjai.</span>
                    <br>
                    <input type="checkbox" id="sendToAllCheckbox" name="sendToAllCheckbox">
                    <label for="sendToAllCheckbox">Üzenet küldése az egész csoportnak</label>
                    <br><br>
                    <label for='groupMessageText'>Az üzenet szövege:</label><br>
                    <textarea rows='10' class='container-fluid form-control' id="groupMessageText"></textarea>
                </form>
            </div>

      
            <!-- Modal footer -->
            <div class='modal-footer'>
                <input type='submit' name='send_group_message_button' value='Küldés' class='btn btn-success' id='sendGroupMessageButton'>
                <button type='button' class='btn btn-primary' data-bs-dismiss='modal'>Bezárás</button>
            </div>
      
        </div>
    </div>
</div>

