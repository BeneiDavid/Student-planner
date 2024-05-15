var chat;

async function openChat(){
    if(this.parentNode.childElementCount == 3){
        var firstChild = this.parentNode.firstChild;
        this.parentNode.removeChild(firstChild);
    }

    removeLogs();
    var currentUserId = await getCurrentUserId();
    var sendToUserId = this.parentNode.id.split("_")[1];
    console.log(this.parentNode.id);
    chat = new Chat(currentUserId, sendToUserId, "chatModal");
    await chat.getLogs();
    
    var sendToUserName = await getFullname(sendToUserId);

    var sendToUserFullname = sendToUserName;
    document.getElementById('chatGroupNameSpan').textContent = sendToUserFullname;
    await $('#chatModal').modal('show');
    
}


function sendButtonClick(event){
    event.preventDefault();
    chat.sendMessage(document.getElementById('messageInput').value);
}

async function init(){
    document.getElementById('sendButton').addEventListener('click', sendButtonClick, false);
    document.getElementById('messageInput').addEventListener('input', setSendButtonColor, false);

    var messageInput = document.getElementById("messageInput");
    var sendButton = document.getElementById("sendButton");

    messageInput.addEventListener("keydown", function(event) {
        if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault(); // Prevent the default Enter key behavior
        sendButton.click(); // Trigger the button click
        }
    });
}


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


// Scrolling down to last message in the chat
$('#chatModal').on('shown.bs.modal', function() {
    document.getElementById('chatBox').scrollTop = document.getElementById('chatBox').scrollHeight;
    $('#chat-form').submit(function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();
        console.log("shoot");
        document.getElementById('chatBox').scrollTop = document.getElementById('chatBox').scrollHeight;
    });

    chat.startRefreshMessages();
});

$('#chatModal').on('hidden.bs.modal', function() {
    chat.stopRefreshMessages();
});


function removeLogs(){
    document.getElementById('chatBox').innerHTML = "";
}


first = true;


class Chat {

    constructor(currentUserId, receivingUserId, modalId) {
        this.currentUserId = currentUserId;
        this.receivingUserId = receivingUserId;
        this.modalId = modalId;
        this.refreshMessages = null; 

    }
    
    startRefreshMessages() {
        if (!this.refreshMessages) {
            this.refreshMessages = setInterval(() => {
                this.refresh();
            }, 5000);
        }
    }

    stopRefreshMessages() {
        if (this.refreshMessages) {
            clearInterval(this.refreshMessages);
            this.refreshMessages = null;
        }
    }

    refresh(){      
        console.log("called");
        this.getLogs();
    }

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
                    console.log(response);
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
                console.log(response);
                if(response.length != 0){
                    var messagesData = response.messages;

                    messagesData.sort((a, b) => {
                        // Convert message_time values to Date objects for comparison
                        const dateA = new Date(a.message_time);
                        const dateB = new Date(b.message_time);
                        
                        // Compare the Date objects
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
      