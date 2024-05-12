



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
                messageImg.title = "Üzenet írása";
                messageText.textContent = "Üzenet írása";
                messageText.classList.add('clickable');
                messageText.style.color = "blue";
                messageDiv.appendChild(messageImg);
                messageDiv.appendChild(messageText);

                var editDiv = document.createElement('div');
                var editImg = document.createElement('img');
                var editText = document.createElement('p');
                editImg.src = 'pictures/edit.svg';
                editImg.style.width = "20px";
                editImg.style.height = "20px";
                editImg.classList.add('clickable');
                editImg.title = "Csoport szerkesztése";
                editText.textContent = "Csoport szerkesztése";
                editText.classList.add('clickable');
                editText.style.color = "darkorange";
                editDiv.appendChild(editImg);
                editDiv.appendChild(editText);
                editText.addEventListener('click', editGroupClick, false);
                editImg.addEventListener('click', editGroupClick, false);

                var deleteDiv = document.createElement('div');
                var deleteImg = document.createElement('img');
                var deleteText = document.createElement('p');
                deleteImg.src = 'pictures/delete.svg';
                deleteImg.style.width = "20px";
                deleteImg.style.height = "20px";
                deleteImg.classList.add('clickable');
                deleteImg.title = "Csoport törlése";
                deleteText.textContent = "Csoport törlése";
                deleteText.classList.add('clickable');
                deleteText.style.color = "red";
                deleteDiv.appendChild(deleteImg);
                deleteDiv.appendChild(deleteText);
                deleteText.addEventListener('click', showConfirmDeleteModal, false);
                deleteImg.addEventListener('click', showConfirmDeleteModal, false);


                containerDiv.appendChild(groupNameText);
                containerDiv.appendChild(tasksDiv);
                containerDiv.appendChild(messageDiv);
                containerDiv.appendChild(editDiv);
                containerDiv.appendChild(deleteDiv);

                
                groupsDiv.appendChild(containerDiv);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}





function init(){
    listGroups();

    document.getElementById('backToGroups').addEventListener('click', backToGroups, false);
}

window.addEventListener('load', init, false);