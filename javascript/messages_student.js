







async function fillTeachersDiv(){
    await $.ajax({
        type: 'POST',
        url: 'queries/list_teacher_and_groups_query.php', 
        data: {},
        dataType: "json",
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            var teachersDiv = document.getElementById('teachersDiv');

            if(response.length != 0){
            var groupData = response.group_data;
            var teacherData = response.teacher_data;
           
            const uniqueTeacherData = Array.from(new Set(teacherData.map(item => item.user_id)))
            .map(user_id => teacherData.find(item => item.user_id === user_id));

                console.log(uniqueTeacherData);


            for (var i = 0; i < uniqueTeacherData.length; i++) {
                var groupNames = [];

                var teacherDiv = document.createElement('div');
                var teacherNameDiv = document.createElement('div');
                var groupNameDiv = document.createElement('div');
                var messageTeacherDiv = document.createElement('div');
                var messageImage = document.createElement('img');
                var messageText = document.createElement('p');

                messageImage.src = "pictures/message.svg";
                messageImage.alt = "Chat megnyitása";
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

                messageTeacherDiv.appendChild(messageImage);
                messageTeacherDiv.appendChild(messageText);

                for(var j = 0; j < groupData.length; j++){
                    if(uniqueTeacherData[i].user_id == groupData[j].group_teacher_id){
                        groupNames.push(groupData[j].group_name);
                    }
                }

                teacherNameDiv.textContent = uniqueTeacherData[i].full_name;
                groupNameDiv.textContent = groupNames.join(", ");

                teacherNameDiv.style.display = "inline-block"
                teacherNameDiv.style.width = "25%";
                teacherNameDiv.style.paddingRight = "50px";
                teacherNameDiv.style.paddingTop = "15px";
                teacherNameDiv.style.paddingLeft = "15px";
                teacherNameDiv.style.paddingBottom = "15px";
                teacherNameDiv.style.verticalAlign = "top";

                groupNameDiv.style.display = "inline-block";
                groupNameDiv.style.textAlign = "left";
                groupNameDiv.style.width = "35%";
                groupNameDiv.style.verticalAlign = "top";
                groupNameDiv.style.paddingTop = "15px";
                groupNameDiv.style.paddingBottom = "15px";
                groupNameDiv.style.paddingRight = "30px";

                messageTeacherDiv.style.display = "inline-block";
                messageTeacherDiv.style.textAlign = "center";
                messageTeacherDiv.style.width = "40%";
                messageTeacherDiv.style.verticalAlign = "top";
                messageTeacherDiv.style.paddingTop = "15px";
                messageTeacherDiv.style.paddingRight = "30px";
                messageTeacherDiv.id = "teacher_" + uniqueTeacherData[i].user_id;

                teacherDiv.classList.add('group-member-div');
                teacherDiv.style.maxWidth = "1000px"; 
                //teacherDiv.id = "teacher_" + uniqueTeacherData[i].user_id;

               
                teacherDiv.appendChild(teacherNameDiv);
                teacherDiv.appendChild(groupNameDiv);
                teacherDiv.appendChild(messageTeacherDiv);
                teachersDiv.appendChild(teacherDiv);
                
            }
            }
            else{
                var noGroupsDiv = document.createElement('div');
                noGroupsDiv.textContent = "Ön még nincs benne egy csoportban sem."
                noGroupsDiv.classList.add('no-created-groups-div');
                teachersDiv.appendChild(noGroupsDiv);
            }
            
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
        });


        var teachersDiv = document.getElementById('teachersDiv');
        
        if(!teachersDiv.firstChild.classList.contains('no-created-groups-div')){

        

            var childDivs = teachersDiv.children;
            for (var i = 0; i < childDivs.length; i++) {

                var messageDiv = childDivs[i].lastChild;
                console.log(messageDiv);
                var sendToUserId = messageDiv.id.split("_")[1];
                await  $.ajax({
                    url: 'queries/all_messages_read_query.php',
                    type: 'POST',
                    data: { 'otherUserId': sendToUserId },
                    success: function(secondResponse) {
                        if(secondResponse == "false"){
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




function init(){
    fillTeachersDiv();
  }
  
  window.addEventListener('load', init, false);