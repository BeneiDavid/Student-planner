



function listGroups(){
    $.ajax({
        type: 'POST',
        url: 'queries/list_groups_query_student.php', 
        data: {},
        dataType: "json",
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            var groupsDiv = document.getElementById('groupsDiv');
            if(response.length != 0){
            var groupData = response.group_data;
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
                messageDiv.id = "teacher_" + groupData[i].group_teacher_id;
                messageText.addEventListener('click', openChat, false);
                messageImg.addEventListener('click', openChat, false);



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
        }
        else{

            var noGroupsDiv = document.createElement('div');
            noGroupsDiv.textContent = "Ön még nincs benne egy csoportban sem."
            noGroupsDiv.classList.add('no-created-groups-div');

            groupsDiv.appendChild(noGroupsDiv);
        }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

function showGroupTasks(){
    var groupsMainDiv = document.getElementById('groupsMainDiv');
    groupsMainDiv.style.display = 'none';

    var groupTasksDiv = document.getElementById('groupTasksDiv');
    groupTasksDiv.style.display = 'block';

    var groupDivElement = this.parentNode.parentNode;
    var taskNameText = groupDivElement.firstElementChild.textContent;

    var groupHeaderName = document.getElementById('groupHeaderName');
    groupHeaderName.textContent = "A(z) \"" + taskNameText + "\" csoport feladatai";
    
    var groupId = groupDivElement.id.split('_')[1];
    $.ajax({
        type: 'POST',
        url: 'queries/group_session_query.php', 
        data: {
            'groupId': groupId
        },
        credentials: 'same-origin',
        success: function(response) {         

        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
console.log(groupId);
    $.ajax({
        type: 'POST',
        url: 'task_query.php', 
        dataType: "json",
        data: {'groupId': groupId},
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            if(response.length == 0){
                var groupTasksBody = document.getElementById('groupTasksBody');
                var tr = document.createElement('tr');
                var noTasksTd =  document.createElement('td');
                noTasksTd.style.backgroundColor = "white";
                noTasksTd.textContent = "A csoportnak még nincsenek feladatai";
                tr.appendChild(noTasksTd);
                while (groupTasksBody.firstChild && groupTasksBody.childElementCount > 1) {
                    groupTasksBody.removeChild(groupTasksBody.children[1]);
                }
                groupTasksBody.appendChild(tr);
            }
            else{   
                fillTaskTable(response, "teacher");
            }
            
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
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


function quitGroup(event){
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

            if(!groupsDiv.hasChildNodes()){
                var noGroupsDiv = document.createElement('div');
                noGroupsDiv.textContent = "Ön még nincs benne egy csoportban sem."
                noGroupsDiv.classList.add('no-created-groups-div');

                groupsDiv.appendChild(noGroupsDiv);
            }

        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });


    
    hideConfirmQuitModal();
}


function backToGroups(){
    var groupsMainDiv = document.getElementById('groupsMainDiv');
    groupsMainDiv.style.display = 'block';

    var groupTasksDiv = document.getElementById('groupTasksDiv');
    groupTasksDiv.style.display = 'none';

    var groupHeaderName = document.getElementById('groupHeaderName');
    groupHeaderName.textContent = 'Csoportok';

    $.ajax({
        type: 'POST',
        url: 'queries/group_session_query.php', 
        data: {
        },
        credentials: 'same-origin',
        success: function(response) {

        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });

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
    console.log("asd");
    var groupQuitConfirmModal = document.getElementById("groupQuitConfirmModal");
    
    if (event.target == groupQuitConfirmModal) {
        groupQuitConfirmModal.style.display = "none";
    }
}