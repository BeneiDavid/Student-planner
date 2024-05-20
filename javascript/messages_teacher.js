// Csoportok listázása a dropdownban
function fillGroupsDropdown(){
    $.ajax({
        type: 'POST',
        url: 'queries/list_groups_query_teacher.php', 
        data: {},
        dataType: "json",
        credentials: 'same-origin',
        success: function(response) {            
            if(response.length != 0){
                var groupData = response.group_data;
                for (var i = 0; i < groupData.length; i++) {
                    var groupDropdownList = document.getElementById('groupDropdownList');
                    var li = document.createElement('li');
                    var a = document.createElement('a');
                    a.classList.add('dropdown-item');
                    a.href = "#";
                    a.textContent = groupData[i].group_name;
                    li.id = "group_" + groupData[i].group_id;
                    li.addEventListener('click', selectGroup, false);
                    li.appendChild(a);
                    groupDropdownList.appendChild(li);
                }
                var chooseGroupButton =  document.getElementById('chooseGroupButton');
                chooseGroupButton.style.display = "block";
            }
            else{
                var noCreatedGroupsDiv =  document.getElementById('noCreatedGroupsDiv');
                noCreatedGroupsDiv.style.display = "block";
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

// Csoport kiválasztása
function selectGroup(){
    var groupId = this.id.split("_")[1];
    var groupName = this.firstChild.textContent;
    var selectedGroupNameHeader = document.getElementById('selectedGroupNameHeader');
    selectedGroupNameHeader.textContent = groupName + " csoport tagjai";

    listGroupMembers(groupId);
}

// Tagok eltávolítása a listából
function clearPreviousMembers(){
    var groupMembersDiv = document.getElementById('groupMembersDiv');
    groupMembersDiv.innerHTML = "";
}

// Tagok listázása
async function listGroupMembers(groupId){
    clearPreviousMembers();

    await $.ajax({
        type: 'POST',
        url: 'queries/group_members_query.php', 
        data: {'groupId': groupId},
        dataType: "json",
        credentials: 'same-origin',
        success: function(response) {
            var groupMembersDiv = document.getElementById('groupMembersDiv');
            if(response.length != 0){
                var studentData = response.student_data;
                for (var i = 0; i < studentData.length; i++) {
                    var memberDiv = document.createElement('div');
                    var studentNameDiv = document.createElement('div');
                    var messageStudentDiv = document.createElement('div');
                    var messageImage = document.createElement('img');
                    var messageText = document.createElement('p');
                    
                    studentNameDiv.style.display = "inline-block"
                    studentNameDiv.style.width = "50%";
                    studentNameDiv.style.paddingRight = "50px";
                    studentNameDiv.style.paddingTop = "15px";
                    studentNameDiv.style.paddingLeft = "15px";
                    studentNameDiv.style.paddingBottom = "15px";
                    studentNameDiv.style.verticalAlign = "top";

                    messageStudentDiv.style.display = "inline-block";
                    messageStudentDiv.style.textAlign = "right";
                    messageStudentDiv.style.width = "50%";
                    messageStudentDiv.style.verticalAlign = "top";
                    messageStudentDiv.style.paddingTop = "15px";
                    messageStudentDiv.style.paddingRight = "30px";
                    messageStudentDiv.id = "user_" + studentData[i].user_id;
   
                    memberDiv.classList.add('group-member-div');
                    memberDiv.style.maxWidth = "665px"; 

                    messageImage.src = "pictures/message.svg";
                    messageImage.style.width = "30px";
                    messageImage.style.height = "30px";
                    messageImage.style.display = "inline-block";
                    messageImage.style.paddingRight = "5px";
                    messageImage.classList.add('clickable');
                    messageImage.addEventListener('click', openChat, false);

                    messageText.textContent = "Chat megnyitása";
                    messageText.style.display = "inline-block";
                    messageText.classList.add('clickable');
                    messageText.addEventListener('click', openChat, false);

                    studentNameDiv.textContent = studentData[i].full_name;
                    
                    messageStudentDiv.appendChild(messageImage);
                    messageStudentDiv.appendChild(messageText);
                    memberDiv.appendChild(studentNameDiv);
                    memberDiv.appendChild(messageStudentDiv);
                    groupMembersDiv.appendChild(memberDiv);
                    
                }
            }
            else{
                var noMembersDiv = document.createElement('div');
                noMembersDiv.textContent = "A csoport még nem rendelkezik tagokkal.";
                noMembersDiv.classList.add('no-members-div');

                groupMembersDiv.appendChild(noMembersDiv);
            }   
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });

    var groupMembersDiv = document.getElementById('groupMembersDiv');
    
    if(!groupMembersDiv.firstChild.classList.contains('no-members-div')){
        var childDivs = groupMembersDiv.children;

        for (var i = 0; i < childDivs.length; i++) {
            var messageDiv = childDivs[i].lastChild;
            var sendToUserId = messageDiv.id.split("_")[1];
            
            await  $.ajax({
                url: 'queries/all_messages_read_query.php',
                type: 'POST',
                data: { 'otherUserId': sendToUserId },
                success: function(secondResponse) {
                    if(secondResponse == "false"){
                        console.log(secondResponse);
                        var unseenMessageDot = createColoredSVG("blue", "35px", "dot");
                        var firstChild = messageDiv.firstChild;
                        messageDiv.insertBefore(unseenMessageDot, firstChild);
                    }
                },
                error: function(error) {
                    console.error('Error in second AJAX request:', error);
                }
            });
        }
    }   
}

// Inicializálás
function init(){
  fillGroupsDropdown();
}

window.addEventListener('load', init, false);