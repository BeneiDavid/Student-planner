<div class='modal fade' id='chatModal'>
    <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content'>
      
            <!-- Modal Header -->
            <div class='modal-header'>
              <p class='modal-title modal-header-text'>Chat - <span id='chatGroupNameSpan'></span></p>
              <button type='button' class='btn-close' data-bs-dismiss='modal'><span class="sr-only">Felugró ablak bezárása</span></button>
            </div>
      
            <!-- Modal body -->
            <div class='modal-body'>

                <div id="chatBox" class='chat-box'></div>
                <form id="chat-form">
                   <!-- <input type="text" id="messageInput" class='message-input form-control' placeholder="Üzenet írása">-->
                    <textarea id="messageInput" class='message-input form-control' placeholder="Üzenet írása"></textarea>
                    <button class='btn btn-secondary align-top' id='sendButton' type='button'>Küldés</button>
                </form>
                <p class='send-message-error' id='sendMessageError'>Üzenet küldési hiba. Kérem próbálja újra!</p>

            </div>
      
            <!-- Modal footer -->
            <div class='modal-footer'>

              <button type='button' class='btn btn-primary' data-bs-dismiss='modal' >Bezárás</button>
            </div>
      
        </div>
    </div>
</div>
