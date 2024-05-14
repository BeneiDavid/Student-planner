







function fillTeachersDiv(){
    $.ajax({
        type: 'POST',
        url: 'queries/list_teacher_and_groups_query.php', 
        data: {},
        dataType: "json",
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
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

                var teachersDiv = document.getElementById('teachersDiv');
                teacherDiv.appendChild(teacherNameDiv);
                teacherDiv.appendChild(groupNameDiv);
                teacherDiv.appendChild(messageTeacherDiv);
                teachersDiv.appendChild(teacherDiv);
                
            }
            }
            
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
        });
}




function init(){
    fillTeachersDiv();
  }
  
  window.addEventListener('load', init, false);