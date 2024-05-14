
// Új csoport létrehozása
function createNewGroup(){
    hideAddMembersModal();
    clearGroupDetailsModalContent();

    $('#groupModal').modal('show');
}

// Új tagok hozzáadása
function addNewMember(event){
    event.preventDefault();
    showAddMembersModal();
}

// Tagok hozzáadása modal cím beállítása
function setMembersModalHeader(){
    var groupName = document.getElementById('groupName');
    var membersModalHeader = document.getElementById('membersModalHeader');
    membersModalHeader.textContent = "Tagok hozzáadása a \"" + groupName.value + "\" csoporthoz";
}

// Tagok hozzáadása modal megjelenítése
function showAddMembersModal(){
    setMembersModalHeader();

    var modalDiv = document.getElementById("modalDiv");
    var addMembersModal = document.getElementById("addMembersModal");
    listNotAddedStudents();
    modalDiv.appendChild(addMembersModal);
    addMembersModal.style.display = "block";
}

// Tagok hozzáadása modal elrejtése
function hideAddMembersModal(){
    var addMembersModal = document.getElementById("addMembersModal");
    addMembersModal.style.display = "none";
}

// Tagok hozzáadása a csoporthoz
function addMembers(event){
    event.preventDefault();

    var searchResults = document.getElementById('searchResults');
    const studentDivs = searchResults.querySelectorAll('div');
    var membersDiv = document.getElementById('membersDiv');
    // Iterate through each result div
    studentDivs.forEach(studentDiv => {
    // Get the last element (checkbox) within the resultDiv
    const checkbox = studentDiv.querySelector('input[type="checkbox"]:last-child');
    
    // Check if the checkbox is checked
    if (checkbox.checked) {
        console.log('Checkbox is checked in this div:', studentDiv.id);
        // Do something if the checkbox is checked

        const newDiv = studentDiv.cloneNode(true);
        
        const svgImage = document.createElement('img');
        svgImage.src = 'pictures/minus.svg'; 
        svgImage.classList.add('clickable');
        svgImage.addEventListener('click', removeStudentFromGroup, false);
     
        newDiv.replaceChild(svgImage, newDiv.lastElementChild);
        var studentId = studentDiv.id.split('_')[1];
        newDiv.id = "member_" + studentId;
        membersDiv.appendChild(newDiv);


    } else {
        console.log('Checkbox is not checked in this div:', studentDiv.id);
        // Do something if the checkbox is not checked
    }
    });


    hideAddMembersModal();
}

// Diák eltávolítása a csoportból
function removeStudentFromGroup(){
    const parentDivId = this.parentNode.id;
    var membersDiv = document.getElementById('membersDiv');

    const divToRemove = document.getElementById(parentDivId);
    membersDiv.removeChild(divToRemove);

}

// Diákok listázása a tag hozzáadása modalban
function listNotAddedStudents(){
    var searchResults = document.getElementById('searchResults');
    searchResults.innerHTML = "";
    $.ajax({
        type: 'POST',
        url: 'queries/list_students_query.php', 
        data: {},
        credentials: 'same-origin',
        success: function(response) {
            console.log(response); 
            var parsedData = JSON.parse(response);
            var student_data = parsedData.student_data;
            if(typeof student_data === 'undefined'){
                document.getElementById('noStudents').style.visibility = "visible";
            }
            else{
                
                for (var i = 0; i < student_data.length; i++) {

                    var student = student_data[i];

                    const memberDivs = document.querySelectorAll('#membersDiv div');

                    const divIDs = [];
                    memberDivs.forEach(div => {
                    divIDs.push(div.id.split("_")[1]);
                    });
                    if (!divIDs.includes(student.user_id)){

                        var studentDiv = document.createElement('div');
                        var studentNameText = document.createElement('p');
                        var studentUserNameText = document.createElement('p');
                        var selectStudentCheckbox = document.createElement('input'); // Change to input for checkbox
                    
                        studentDiv.id = "studentDiv_" + student.user_id;
                        studentDiv.classList.add("student-data");
                        studentNameText.textContent = student.full_name;
                        studentUserNameText.textContent = student.username;
                        selectStudentCheckbox.type = 'checkbox'; // Set input type to checkbox

                        studentDiv.appendChild(studentNameText);
                        studentDiv.appendChild(studentUserNameText);
                        studentDiv.appendChild(selectStudentCheckbox);
                    
                        searchResults.appendChild(studentDiv);
                    }
                }

                if(!searchResults.hasChildNodes()){
                    document.getElementById('noStudents').style.visibility = "visible";
                }
                else{
                    document.getElementById('noStudents').style.visibility = "collapse";
                }
            }
  
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

// Keresés eredményének megjelenítése
function showSearchResults(){
    const searchTerm = searchInput.value.toLowerCase();
    const studentDivs = Array.from(searchResults.getElementsByClassName('student-data'));
    var foundResult = false;
    studentDivs.forEach(studentDiv => {
        const nameElement = studentDiv.querySelector('p:first-child');
        const usernameElement = studentDiv.querySelector('p:nth-child(2)');
        const name = nameElement ? nameElement.textContent.toLowerCase() : '';
        const username = usernameElement ? usernameElement.textContent.toLowerCase() : '';
        if (name.includes(searchTerm) || username.includes(searchTerm)) {
          studentDiv.style.display = 'block';
          foundResult = true;
        } else {
          studentDiv.style.display = 'none';
        }
      });
    
      if (searchTerm != "" && !foundResult) {
        document.getElementById('noResultText').style.visibility = "visible";
      }
      else{
        document.getElementById('noResultText').style.visibility = "collapse";
      }
}

// Csoport adatainak mentése
function saveGroup(event){
    event.preventDefault();
    const groupName = document.getElementById('groupName');

    if(groupName.value == ""){
        document.getElementById('groupNameError').style.display = 'block';
        return;
    }
    else{
        document.getElementById('groupNameError').style.display = 'none';

        var groupAddData = $('#groupModal').serialize();
        console.log(groupAddData);
        var membersDiv = document.getElementById('membersDiv');

        var childDivs = membersDiv.querySelectorAll('div');
        var studentIds = [];
        // Loop through each div
        childDivs.forEach(function(div) {
            console.log(div.id);
            studentIds.push(div.id.split('_')[1]);
        });
        
        var groupEditId = document.getElementById('groupEditId');

        if(groupEditId.value == ''){
            $.ajax({
                type: 'POST',
                url: 'modals/group_details_modal.php', 
                data: {
                    'groupAddData': groupAddData,
                    'groupName': groupName.value,
                    'studentIds': studentIds
                },
                credentials: 'same-origin',
                success: function(response) {
                    console.log(response);
                    groupName.value  = "";
                    membersDiv.innerHTML = "";
                    listGroups();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
        else{
            $.ajax({
                type: 'POST',
                url: 'queries/group_edit_query.php', 
                data: {
                    'groupAddData': groupAddData,
                    'groupName': groupName.value,
                    'studentIds': studentIds,
                    'groupId': groupEditId.value
                },
                credentials: 'same-origin',
                success: function(response) {
                    console.log(response + "asd");
                    groupName.value  = "";
                    membersDiv.innerHTML = "";
                    listGroups();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        $('#groupModal').modal('hide');
    }
}

// Csoportok listázása
function listGroups(){
    $.ajax({
        type: 'POST',
        url: 'queries/list_groups_query_teacher.php', 
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
                messageImg.title = "Csoportüzenet írása";
                messageText.textContent = "Csoportüzenet írása";
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

// Csoport feladatai megjelenítése
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

    $.ajax({
        type: 'POST',
        url: 'task_query.php', 
        dataType: "json",
        data: {'groupId': groupId },
        credentials: 'same-origin',
        success: function(response) {
            fillTaskTable(response, "teacher");
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

function refreshGroupTasks(){

    var groupId = "";
    
    $.ajax({
        type: 'POST',
        url: 'task_query.php', 
        dataType: "json",
        data: {'groupId': groupId },
        credentials: 'same-origin',
        success: function(response) {
            fillTaskTable(response, "teacher");
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
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


// Törlés megerősítése modal elrejtése
function hideConfirmDeleteModal(){
    var groupDeleteConfirmModal = document.getElementById("groupDeleteConfirmModal");
    groupDeleteConfirmModal.style.display = "none";
}

// Törlés megerősítése modal megjelenítése
function showConfirmDeleteModal(){
    var groupDivElement = this.parentNode.parentNode;
    var taskNameText = groupDivElement.firstElementChild.textContent;
    var groupDeleteConfirmModal = document.getElementById("groupDeleteConfirmModal");
    var groupToDeleteSpan = document.getElementById("groupToDeleteSpan");
    var groupToDeleteId = document.getElementById("groupToDeleteId");

    groupToDeleteId.value = groupDivElement.id;
    groupToDeleteSpan.textContent = taskNameText;
    groupDeleteConfirmModal.style.display = "block";
}

// Csoport törlése
function deleteGroup(event){
    event.preventDefault();

    var groupToDeleteId = document.getElementById("groupToDeleteId");
    var groupId = groupToDeleteId.value.split('_')[1];
    var groupsDiv = document.getElementById("groupsDiv");
   

    $.ajax({
        type: 'POST',
        url: 'queries/delete_group_query.php', 
        data: {
            'groupId': groupId
        },
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            var divToDelete = document.getElementById(groupToDeleteId.value);
            groupsDiv.removeChild(divToDelete);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
    
    hideConfirmDeleteModal();
}

// Csoport adatai modal kiürítése
function clearGroupDetailsModalContent(){
    var groupEditId = document.getElementById('groupEditId');
    if(groupEditId.value != ''){
        groupEditId.value = '';
        var membersDiv = document.getElementById('membersDiv');
        membersDiv.innerHTML = "";
        var groupName = document.getElementById('groupName');
        groupName.value = "";
    }
}

// Csoport módosítása modal megjelenítése
function editGroupClick(){

    clearGroupDetailsModalContent();

    var groupDivElement = this.parentNode.parentNode;
    var taskNameText = groupDivElement.firstElementChild.textContent;
    document.getElementById('groupName').value = taskNameText;
    var groupId = groupDivElement.id.split('_')[1];
    var groupEditId = document.getElementById('groupEditId');
    groupEditId.value = groupId;

    $.ajax({
        type: 'POST',
        url: 'queries/group_members_query.php', 
        data: {
            'groupId': groupId
        },
        credentials: 'same-origin',
        success: function(response) {
            var membersDiv = document.getElementById('membersDiv');
            var parsedData = JSON.parse(response);
            var student_data = parsedData.student_data;
            if(typeof student_data !== 'undefined'){

           
            for (var i = 0; i < student_data.length; i++) {

                var student = student_data[i];

                const newDiv = document.createElement('div');
                var studentNameText = document.createElement('p');
                var studentUserNameText = document.createElement('p');
                var selectStudentCheckbox = document.createElement('input'); // Change to input for checkbox
            
                newDiv.id = "newDiv_" + student.user_id;
                newDiv.classList.add("student-data");
                studentNameText.textContent = student.full_name;
                studentUserNameText.textContent = student.username;
                selectStudentCheckbox.type = 'checkbox'; // Set input type to checkbox

                const svgImage = document.createElement('img');
                svgImage.src = 'pictures/minus.svg'; 
                svgImage.classList.add('clickable');
                svgImage.addEventListener('click', removeStudentFromGroup, false);

                newDiv.appendChild(studentNameText);
                newDiv.appendChild(studentUserNameText);
                newDiv.appendChild(svgImage);
            
                membersDiv.appendChild(newDiv);
            }
        }

        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });


    $('#groupModal').modal('show');
}

// Oldal betöltésének funkciói
function init(){
    listGroups();

    document.getElementById('createNewGroup').addEventListener('click', createNewGroup, false);
    document.getElementById('addNewMember').addEventListener('click', addNewMember, false);
    document.getElementById('addMembers_cancelButton').addEventListener('click', hideAddMembersModal, false);
    document.getElementById('addMembers_xButton').addEventListener('click', hideAddMembersModal, false);
    document.getElementById('groupDelete_cancelButton').addEventListener('click', hideConfirmDeleteModal, false);
    document.getElementById('groupDelete_xButton').addEventListener('click', hideConfirmDeleteModal, false);
    document.getElementById('addMembers').addEventListener('click', addMembers, false);
    document.getElementById('searchInput').addEventListener('input', showSearchResults ,false);
    document.getElementById('saveGroupButton').addEventListener('click', saveGroup, false);
    document.getElementById('confirmGroupDelete').addEventListener('click', deleteGroup, false);

    document.getElementById('add-group-task-button').addEventListener('click', addTask, false);
    document.getElementById('backToGroups').addEventListener('click', backToGroups, false);
}

window.addEventListener('load', init, false);

// Modalból kikattintás kezelése
window.onclick = function(event) {
    var addMembersModal = document.getElementById("addMembersModal");
    if (event.target == addMembersModal) {
        addMembersModal.style.display = "none";
    }

    var groupDeleteConfirmModal = document.getElementById("groupDeleteConfirmModal");
    if (event.target == groupDeleteConfirmModal) {
        groupDeleteConfirmModal.style.display = "none";
    }
}