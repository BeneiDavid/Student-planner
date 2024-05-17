

function showGroupMessageModal(){
    var groupMessageGroupNameSpan = document.getElementById('groupMessageGroupNameSpan');
    var groupId = this.parentNode.parentNode.id.split("_")[1];
    listStudents(groupId);

    groupMessageGroupNameSpan.textContent = this.parentNode.parentNode.firstChild.textContent;
   
    $('#groupMessageModal').modal('show');
}


$('#groupMessageModal').on('shown.bs.modal', function() {
    console.log("modal show");
    $('.tooltips').tooltip({
        container: $(this)
    });
});

function listStudents(groupId){
    var searchStudentResults = document.getElementById('searchStudentResults');
    searchStudentResults.innerHTML = "";
    document.getElementById('noSearchResultText').style.display = "none";
    document.getElementById('noStudentsSelected').style.display = "none";
    document.getElementById('noMessageSpecified').style.display = "none";
    document.getElementById('groupMessageText').value = "";
    document.getElementById('noSearchResultText').value = "";
    document.getElementById('sendToAllCheckbox').checked = false;
    $.ajax({
        type: 'POST',
        url: 'queries/group_members_query.php', 
        data: {'groupId': groupId },
        credentials: 'same-origin',
        success: function(response) {
            console.log(response); 
            var parsedData = JSON.parse(response);
            var student_data = parsedData.student_data;
            if(typeof student_data === 'undefined'){
                document.getElementById('noRegisteredStudents').style.display = "block";
                document.getElementById('addMembersForm').style.display = "none";
                document.getElementById('sendGroupMessageButton').style.visibility = "collapse";
                
            }
            else{
                
                for (var i = 0; i < student_data.length; i++) {

                    var student = student_data[i];



                        var studentDiv = document.createElement('div');
                        var studentNameText = document.createElement('p');
                        var studentUserNameText = document.createElement('p');
                        var selectStudentCheckbox = document.createElement('input'); // Change to input for checkbox
                    
                        studentDiv.id = "studentDiv_" + student.user_id;
                        studentDiv.classList.add("student-data");
                        studentDiv.classList.add("group-message-student-data");
                        studentNameText.textContent = student.full_name;
                        studentUserNameText.textContent = student.username;
                        selectStudentCheckbox.type = 'checkbox'; // Set input type to radio

                        studentDiv.appendChild(studentNameText);
                        studentDiv.appendChild(studentUserNameText);
                        studentDiv.appendChild(selectStudentCheckbox);
                    
                        searchStudentResults.appendChild(studentDiv);
                    
                }

                if(!searchStudentResults.hasChildNodes()){
                    document.getElementById('noRegisteredStudents').style.display = "block";
                    document.getElementById('addMembersForm').style.display = "none";
                    document.getElementById('sendGroupMessageButton').style.visibility = "collapse";
                }
                else{
                    document.getElementById('noRegisteredStudents').style.display = "none";
                    document.getElementById('addMembersForm').style.display = "block";
                    document.getElementById('sendGroupMessageButton').style.visibility = "visible";
                }

                var checkboxes = document.querySelectorAll('input[type="checkbox"]');

                checkboxes.forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        document.getElementById('noStudentsSelected').style.display = "none";
                    });
                });
            }
  
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

function showGroupMessageSearchResults(){
    var messageSearchInput =  document.getElementById('messageSearchInput')
    const searchTerm = messageSearchInput.value.toLowerCase();
    var searchStudentResults = document.getElementById('searchStudentResults');
    const studentDivs = Array.from(searchStudentResults.getElementsByClassName('group-message-student-data'));
    var foundResult = false;
    studentDivs.forEach(studentDiv => {
        const nameElement = studentDiv.querySelector('p:first-child');
        const usernameElement = studentDiv.querySelector('p:nth-child(2)');
        const name = nameElement ? nameElement.textContent.toLowerCase() : '';
        const username = usernameElement ? usernameElement.textContent.toLowerCase() : '';
        if (name.includes(searchTerm) || username.includes(searchTerm)) {
          studentDiv.style.display = 'block';
          foundResult = true;
        } 
        else {
          studentDiv.style.display = 'none';
        }
      });
    
      if (searchTerm != "" && !foundResult) {
        document.getElementById('noSearchResultText').style.display = "block";
      }
      else{
        document.getElementById('noSearchResultText').style.display = "none";
      }
}

function sendToAllChanged(){
    document.getElementById('noStudentsSelected').style.display = "none";
    var checked = this.checked;
    var searchStudentResults = document.getElementById('searchStudentResults');
    var checkboxes = searchStudentResults.querySelectorAll("input[type='checkbox']");

    if(checked){
        checkboxes.forEach(function(checkbox) {
            checkbox.disabled = true;
            checkbox.checked = false;
          });
    }
    else{
        checkboxes.forEach(function(checkbox) {
            checkbox.disabled = false;
          });
    }
}


function sendGroupMessage(event){
    event.preventDefault();

    var message = document.getElementById('groupMessageText').value;

    if(message.trim() !== ''){

            
        document.getElementById('noMessageSpecified').style.display = "none"; 
        document.getElementById('noStudentsSelected').style.display = "none";
    

        var sendToAll = document.getElementById('sendToAllCheckbox').checked;
        console.log(sendToAll);

        var searchStudentResults = document.getElementById("searchStudentResults");

        // Get all child divs within the parent div
        var childDivs = searchStudentResults.getElementsByTagName("div");

        // Create an array to store the IDs
        var studentIds = [];

        // Iterate through each child div and get its ID
    

        if(!sendToAll){
            for (var i = 0; i < childDivs.length; i++) {
                if(childDivs[i].lastChild.checked){
                var divId = childDivs[i].id;
                studentIds.push(divId.split("_")[1]);
                }
            }
        }
        else{
            message = "* CSOPORT ÜZENET * - " + message;
            for (var i = 0; i < childDivs.length; i++) {
                var divId = childDivs[i].id;
                studentIds.push(divId.split("_")[1]);
            }
        }

        if(!sendToAll && studentIds.length == 0){
            document.getElementById('noStudentsSelected').style.display = "block";
        }
        else{
            $.ajax({
                type: 'POST',
                url: 'queries/send_group_message_query.php', 
                data: {
                    'studentIds': studentIds,
                    'message': message
                },
                dataType: "text",
                credentials: 'same-origin',
                success: function(response) {
                    console.log(response);
                    
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });

            $('#groupMessageModal').modal('hide');
        }
    }
    else{
        document.getElementById('noMessageSpecified').style.display = "block";
        document.getElementById('noStudentsSelected').style.display = "none";
    }
}

function init(){
    document.getElementById('messageSearchInput').addEventListener('input', showGroupMessageSearchResults ,false);
    document.getElementById('sendToAllCheckbox').addEventListener('change', sendToAllChanged, false)
    document.getElementById('sendGroupMessageButton').addEventListener('click', sendGroupMessage, false);

    
}

window.addEventListener('load', init, false);