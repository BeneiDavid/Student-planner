
// Új csoport létrehozása
function createNewGroup(){
    hideAddMembersModal();


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
        svgImage.src = 'pictures/minus.svg'; // Replace 'your_svg_file.svg' with your SVG file path
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
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });

        $('#groupModal').modal('hide');
    }
}


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
                editImg.addEventListener('click', editGroup, false);
                editDiv.addEventListener('click', editGroup, false);

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
                deleteImg.addEventListener('click', showConfirmDeleteModal, false);
                deleteDiv.addEventListener('click', showConfirmDeleteModal, false);


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


function hideConfirmDeleteModal(){
    var groupDeleteConfirmModal = document.getElementById("groupDeleteConfirmModal");
    groupDeleteConfirmModal.style.display = "none";
}

function showConfirmDeleteModal(){
    var groupDivElement = this.parentNode;
    var taskNameText = groupDivElement.firstElementChild.textContent;
    var groupDeleteConfirmModal = document.getElementById("groupDeleteConfirmModal");
    var groupToDeleteSpan = document.getElementById("groupToDeleteSpan");
    var groupToDeleteId = document.getElementById("groupToDeleteId");

    groupToDeleteId.value = groupDivElement.id;
    groupToDeleteSpan.textContent = taskNameText;
    groupDeleteConfirmModal.style.display = "block";
}

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

function editGroup(){


    $('#groupModal').modal('show');
}


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
}

window.addEventListener('load', init, false);


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