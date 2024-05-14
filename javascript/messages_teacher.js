




function fillGroupsDropdown(){
    $.ajax({
        type: 'POST',
        url: 'queries/list_groups_query_teacher.php', 
        data: {},
        dataType: "json",
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            
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
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
        });
}

function selectGroup(){
    var groupId = this.id.split("_")[1];
    var groupName = this.firstChild.textContent;

    document.getElementById('selectedGroupNameHeader').textContent = groupName + " csoport tagjai";

    listGroupMembers(groupId);
}

function clearPreviousMembers(){
    var groupMembersDiv = document.getElementById('groupMembersDiv');
    groupMembersDiv.innerHTML = "";
}

function listGroupMembers(groupId){

    clearPreviousMembers();

    $.ajax({
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
                    //memberDiv.id = "user_" + studentData[i].user_id;

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
                noMembersDiv.style.color = "blue";
                /* var textPart1 = document.createElement('p');
                var textPart2 = document.createElement('p');
                textPart1.textContent = "A csoport még nem rendelkezik tagokkal.";
                textPart2.textContent = "A Csoportok menüpontban kezelheti egy csoport tagjait.";*/
                groupMembersDiv.appendChild(noMembersDiv);
                //groupMembersDiv.appendChild(textPart2);
            }
            
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
        });
}





function init(){
  fillGroupsDropdown();
}

window.addEventListener('load', init, false);