var chat;

// Chat modal kinyitása
async function openChat(){
    if(this.parentNode.childElementCount == 3){
        var firstChild = this.parentNode.firstChild;
        this.parentNode.removeChild(firstChild);
    }

    removeLogs();

    var currentUserId = await getCurrentUserId();
    var sendToUserId = this.parentNode.id.split("_")[1];

    chat = new Chat(currentUserId, sendToUserId, "chatModal");
    await chat.getLogs();
    
    var sendToUserName = await getFullname(sendToUserId);
    var sendToUserFullname = sendToUserName;

    document.getElementById('chatGroupNameSpan').textContent = sendToUserFullname;
    await $('#chatModal').modal('show');
    
}

// Üzenet küldés gomb kattintás kezelése
function sendButtonClick(event){
    event.preventDefault();
    chat.sendMessage(document.getElementById('messageInput').value);
}

// Inicializálás
async function init(){
    document.getElementById('sendButton').addEventListener('click', sendButtonClick, false);
    document.getElementById('messageInput').addEventListener('input', setSendButtonColor, false);

    var messageInput = document.getElementById("messageInput");
    var sendButton = document.getElementById("sendButton");

    messageInput.addEventListener("keydown", function(event) {
        if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault();
        sendButton.click();
        }
    });
}

// Küldés gomb szín beállítása
function setSendButtonColor(){
    var sendButton = document.getElementById('sendButton');
    
    if(this.value.trim() === '' && !sendButton.classList.contains('btn-secondary')){
        sendButton.classList.remove('btn-success');
        sendButton.classList.add('btn-secondary');
    }
    else if(this.value.trim() !== '' && !sendButton.classList.contains('btn-success')){
        sendButton.classList.remove('btn-secondary');
        sendButton.classList.add('btn-success');
    }
   
}

window.addEventListener('load', init, false);


// Chat megnyitás kezelése
$('#chatModal').on('shown.bs.modal', function() {
    document.getElementById('chatBox').scrollTop = document.getElementById('chatBox').scrollHeight;
    $('#chat-form').submit(function(event) {
        event.preventDefault();
        document.getElementById('chatBox').scrollTop = document.getElementById('chatBox').scrollHeight;
    });

    chat.startRefreshMessages();
});

// Chat elrejtés kezelése
$('#chatModal').on('hidden.bs.modal', function() {
    chat.stopRefreshMessages();
});

// Chat tartalmának törlése
function removeLogs(){
    document.getElementById('chatBox').innerHTML = "";
}


// Chat osztály
class Chat {

    // Constructor

    constructor(currentUserId, receivingUserId, modalId) {
        this.currentUserId = currentUserId;
        this.receivingUserId = receivingUserId;
        this.modalId = modalId;
        this.refreshMessages = null; 

    }

    // Methods
    
    // Üzenet frissítés elindítása
    startRefreshMessages() {
        if (!this.refreshMessages) {
            this.refreshMessages = setInterval(() => {
                this.refresh();
            }, 5000);
        }
    }

    // Üzenet frissítés leállítása
    stopRefreshMessages() {
        if (this.refreshMessages) {
            clearInterval(this.refreshMessages);
            this.refreshMessages = null;
        }
    }

    // Üzenetek frissítése
    refresh(){      
        this.getLogs();
    }

    // Üzenet küldése
    sendMessage(message){   
        var thisClass = this;  
        var receivingUserId = this.receivingUserId;
        if(message.trim() !== ''){
            $.ajax({
                type: 'POST',
                url: 'queries/send_message_query.php', 
                data: {
                    'message': message,
                    'receivingUserId': receivingUserId
                },
                credentials: 'same-origin',
                success: function(response) {
                    document.getElementById('sendMessageError').style.display = "none";
                    document.getElementById('messageInput').value = "";
                    thisClass.refresh();
                    document.getElementById('chatBox').scrollTop = document.getElementById('chatBox').scrollHeight;
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    document.getElementById('sendMessageError').style.display = "block";
                }
            });
        }
    }

    // Beszélgetés lekérdezése
    async getLogs(){
        const receivingUserId = this.receivingUserId;
        await $.ajax({
            type: 'POST',
            url: 'queries/get_messages_query.php', 
            data: {
                'receivingUserId': receivingUserId
            },
            dataType: "json",
            credentials: 'same-origin',
            success: function(response) {
                if(response.length != 0){
                    var messagesData = response.messages;

                    messagesData.sort((a, b) => {
                        const dateA = new Date(a.message_time);
                        const dateB = new Date(b.message_time);
                        return dateA - dateB;
                    });

                    var chatBox = document.getElementById('chatBox');
                    var numberOfMessages = chatBox.childElementCount;

                    for (var i = numberOfMessages; i < messagesData.length; i++) {

                        var messageDiv = document.createElement('div');
                        messageDiv.style.maxWidth = "400px";
                        messageDiv.style.marginTop = "10px";
                        messageDiv.style.padding = "10px";
                        messageDiv.style.borderRadius = "10px";
                        messageDiv.textContent = messagesData[i].message_text;

                        if(messagesData[i].receiver_id != receivingUserId){
                            messageDiv.style.marginLeft = "10px";
                            messageDiv.classList.add('received-message');
                            messageDiv.style.border = "2px solid grey";
                        }
                        else{
                            messageDiv.style.marginRight = "10px";
                            messageDiv.classList.add('sent-message');
                            messageDiv.style.display = "block";
                            messageDiv.style.border = "2px solid blue";
                        }
                       
                        if(i == messagesData.length){
                            messageDiv.id = "lastChildId";
                        }
                       
                        chatBox.appendChild(messageDiv);
                    }

                    if(numberOfMessages != messagesData.length){
                        document.getElementById('chatBox').scrollTop = document.getElementById('chatBox').scrollHeight;
                    }
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
}
      