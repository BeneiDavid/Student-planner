<?php










?>


<div class='modal fade' id='groupModal'>
        <div class='modal-dialog modal-dialog-centered modal-lg'>
          <div class='modal-content'>
      
            <!-- Modal Header -->
            <div class='modal-header'>
              <h4 class='modal-title'>Csoport adatai</h4>
              <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
            </div>
      
            <!-- Modal body -->
            <div class='modal-body'>
                <form method='post'>
                    <input type='hidden' id='existingTask'>
                    <div id='modalDiv'></div>
                    <label for='groupname'>Csoport neve:</label> 
                    <input type='text' class='task-input task-name' id='groupName' name='groupname' maxlength="50"><span class="error label-name-error" id="groupNameError">A csoport neve nem lehet üres!</span>
                    <br><br>
                    <label for='members'>Tagok:</label>
                    
                    <div name='members' id='membersDiv' class='members-div div-with-border'>
                        <!-- Itt lesznek a tagok adatai -->
                    </div>
                    <br><br>
                    <div class='new-task-item  clickable no-select new-member-button' id='addNewMember'>
                        <button class='add-task-button'>
                            <img class='add-task-icon' src='pictures/plus-square.svg' alt='Tag hozzáadása'>  Új tag hozzáadása
                        </button>
                    </div>
                
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
