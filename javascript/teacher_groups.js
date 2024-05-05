
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


function init(){
    document.getElementById('createNewGroup').addEventListener('click', createNewGroup, false);
    document.getElementById('addNewMember').addEventListener('click', addNewMember, false);
    document.getElementById('addMembers_cancelButton').addEventListener('click', hideAddMembersModal, false);
    document.getElementById('addMembers_xButton').addEventListener('click', hideAddMembersModal, false);
    document.getElementById('addMembers').addEventListener('click', addMembers, false);
    document.getElementById('searchInput').addEventListener('input', showSearchResults ,false);
}

window.addEventListener('load', init, false);


window.onclick = function(event) {
    var addMembersModal = document.getElementById("addMembersModal");
    if (event.target == addMembersModal) {
        addMembersModal.style.display = "none";
    }
  }