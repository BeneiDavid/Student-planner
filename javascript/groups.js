



function listGroups(){
    $.ajax({
        type: 'POST',
        url: 'queries/list_groups_query_student.php', 
        data: {},
        credentials: 'same-origin',
        success: function(response) {
            var parsedData = JSON.parse(response);
            var groupData = parsedData.group_data;
            var groupsDiv = document.getElementById('groupsDiv');
            groupsDiv.innerHTML = "";
            for (var i = 0; i < groupData.length; i++) {
                var containerDiv = document.createElement('div');
                containerDiv.id = "group_" + groupData[i].group_id;

                const groupNameText =  document.createElement('p');
                groupNameText.textContent = groupData[i].group_name;

                var tasksDiv = document.createElement('div');
                var tasksImg = document.createElement('img');
                var tasksText = document.createElement('p');
                tasksImg.src = 'pictures/list.svg';
                tasksImg.style.width = "20px";
                tasksImg.style.height = "20px";
                tasksImg.classList.add('clickable');
                tasksImg.title = "Feladatok";
                tasksText.textContent = "Feladatok";
                tasksText.classList.add('clickable');
                tasksText.style.color = "green";
                tasksDiv.appendChild(tasksImg);
                tasksDiv.appendChild(tasksText);
                tasksImg.addEventListener('click', showGroupTasks, false);
                tasksText.addEventListener('click', showGroupTasks, false);



                var messageDiv = document.createElement('div');
                var messageImg = document.createElement('img');
                var messageText = document.createElement('p');
                messageImg.src = 'pictures/message.svg';
                messageImg.style.width = "20px";
                messageImg.style.height = "20px";
                messageImg.classList.add('clickable');
                messageImg.title = "Üzenet írása az oktatónak";
                messageText.textContent = "Üzenet írása az oktatónak";
                messageText.classList.add('clickable');
                messageText.style.color = "blue";
                messageDiv.appendChild(messageImg);
                messageDiv.appendChild(messageText);



                var quitGroupDiv = document.createElement('div');
                var quitGroupImg = document.createElement('img');
                var quitGroupText = document.createElement('p');
                quitGroupImg.src = 'pictures/quit.svg';
                quitGroupImg.style.width = "20px";
                quitGroupImg.style.height = "20px";
                quitGroupImg.classList.add('clickable');
                quitGroupImg.title = "Kilépés a csoportból";
                quitGroupText.textContent = "Kilépés a csoportból";
                quitGroupText.classList.add('clickable');
                quitGroupText.style.color = "red";
                quitGroupDiv.appendChild(quitGroupImg);
                quitGroupDiv.appendChild(quitGroupText);
                quitGroupText.addEventListener('click', showConfirmQuitModal, false);
                quitGroupImg.addEventListener('click', showConfirmQuitModal, false);


                containerDiv.appendChild(groupNameText);
                containerDiv.appendChild(tasksDiv);
                containerDiv.appendChild(messageDiv);
                containerDiv.appendChild(quitGroupDiv);

                
                groupsDiv.appendChild(containerDiv);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

function showGroupTasks(){

}

function showConfirmQuitModal(){
    var groupDivElement = this.parentNode.parentNode;
    var taskNameText = groupDivElement.firstElementChild.textContent;
    var groupQuitConfirmModal = document.getElementById("groupQuitConfirmModal");
    var groupQuitSpan = document.getElementById("groupQuitSpan");
    var groupQuitId = document.getElementById("groupQuitId");

    groupQuitId.value = groupDivElement.id;
    groupQuitSpan.textContent = taskNameText;
    groupQuitConfirmModal.style.display = "block";
}

function hideConfirmQuitModal(){
    var groupQuitConfirmModal = document.getElementById("groupQuitConfirmModal");
    groupQuitConfirmModal.style.display = "none";
}


function quitGroup(){
    event.preventDefault();

    var groupQuitId = document.getElementById("groupQuitId");
    var groupId = groupQuitId.value.split('_')[1];
    var groupsDiv = document.getElementById("groupsDiv");
   

    $.ajax({
        type: 'POST',
        url: 'queries/quit_group_query.php', 
        data: {
            'groupId': groupId
        },
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            var divToDelete = document.getElementById(groupQuitId.value);
            groupsDiv.removeChild(divToDelete);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
    
    hideConfirmQuitModal();
}


function init(){
    listGroups();
    document.getElementById('groupQuit_cancelButton').addEventListener('click', hideConfirmQuitModal, false);
    document.getElementById('groupQuit_xButton').addEventListener('click', hideConfirmQuitModal, false);
    document.getElementById('confirmGroupQuit').addEventListener('click', quitGroup, false);


    document.getElementById('backToGroups').addEventListener('click', backToGroups, false);
}

window.addEventListener('load', init, false);

window.onclick = function(event) {
    var groupQuitConfirmModal = document.getElementById("groupQuitConfirmModal");
    if (event.target == groupQuitConfirmModal) {
        groupQuitConfirmModal.style.display = "none";
    }
}