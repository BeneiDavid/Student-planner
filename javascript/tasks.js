
function taskClick(){
    var cellTaskId = this.id;
    var taskId = cellTaskId.split("-")[1];
    console.log(taskId);
    $.ajax({
        type: 'POST',
        url: 'task_details_by_id.php', 
        dataType: "html",
        data: {'taskId': taskId},
        credentials: 'same-origin',
        success: function(response) {
           addTask(null, response);
        }
        ,
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
    
}


function fillTaskTable(response, type){

            var hasData = false;
            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    if (response[key] && response[key].length > 0) {
                        hasData = true;
                        break;
                    }
                }
            }

            if(type == "daily"){
                var taskBody = document.getElementById("taskBody");
             while (taskBody.firstChild && taskBody.childElementCount > 1) {
                taskBody.removeChild(taskBody.firstChild);
            }

            if(!hasData){
                return;
            }
            var tasks = response.tasks;

            tasks.sort(function (a, b) {
                var startTimeComparison = a.start_time.localeCompare(b.start_time);
            
                if (startTimeComparison === 0) {
                    return a.end_time.localeCompare(b.end_time);
                }

                return startTimeComparison;
            });

            var taskLabels = response.task_labels;
            var labels = response.labels;
            tasks.forEach(function(task) {
                var taskId = task.task_id;
                var taskTitle = task.title;
                var row = document.createElement('tr');
                var time = document.createElement('td');
                var userTask = document.createElement('td');
                var userTaskDiv = document.createElement('div');
                var taskTime = "";

                if(task.start_time_enabled == 1){
                    var startparts = task.start_time.split(":");
                    var starthour = startparts[0];
                    var startminute = startparts[1];
                    var startformattedTime = starthour + ":" + startminute;
                    taskTime = startformattedTime;
                }
                taskTime += " - ";
                if(task.end_time_enabled == 1){
                    var endparts = task.end_time.split(":");
                    var endhour = endparts[0];
                    var endminute = endparts[1];
                    var endformattedTime = endhour + ":" + endminute;
                    taskTime += endformattedTime;
                }
                
                var taskNameText = document.createElement('div');

                taskNameText.classList.add('task-text-name');
                time.textContent = taskTime;
                time.classList.add("first-col");
               // userTaskDiv.textContent = taskTitle;
                userTaskDiv.classList.add("text-container");
                userTask.classList.add("second-col");
                userTask.classList.add("clickable");
                userTask.classList.add("no-select");
                userTask.classList.add("colored-section");
                
                userTask.style.setProperty('--before-bg-color', task.task_color);
                userTask.id = "task-" + taskId;
                userTask.addEventListener('click', taskClick, false);
                taskNameText.textContent = taskTitle;
                userTaskDiv.appendChild(taskNameText);
                // Címkék kezelése
                if (taskLabels && Array.isArray(taskLabels)) {
                    
                    for (var i = 0; i < taskLabels.length; i++) {
                        // Access label properties
                        if(taskLabels[i].task_id == task.task_id){
                            for (var i = 0; i < labels.length; i++) {
                            
                                if(taskLabels[i].label_id == labels[i].label_id && taskLabels[i].task_id == task.task_id){
                                    var newDiv = document.createElement('div');
                                    var newP = document.createElement('p');
                                    var newImg = document.createElement('img');
                                    newP.textContent = labels[i].label_name;
                                    newImg.src = labels[i].label_symbol;
                                    newDiv.classList.add('ellipse');
                                    newP.classList.add('preview-text');
                                    newImg.classList.add('preview-image');
                                    newDiv.classList.add('clickable');
                                    newDiv.classList.add("no-select");
                                    var rgb = hexToRgb(labels[i].label_color);
    
                                    var rgbString = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
                                    var textColor = getContrastColor(rgbString);
                                    newP.style.color = textColor; 
                                
                                    if(textColor == 'white'){
                                    newImg.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
                                    }

                                    newDiv.style.backgroundColor = labels[i].label_color;
                
                                    if(labels[i].label_symbol != ""){
                                    newImg.style.display = "inline-block";
                                    }
                                    
                                    newDiv.appendChild(newP);
                                    newDiv.appendChild(newImg);
                
                                    userTaskDiv.appendChild(newDiv);
                                }
                            }
                        }
                    }
                }

                userTask.appendChild(userTaskDiv);
                row.appendChild(time);
                row.appendChild(userTask);
                var newTaskRow = document.getElementById("newTaskRow");
                var taskBody = document.getElementById("taskBody");
                taskBody.insertBefore(row, newTaskRow);
            });
            }
            else if(type == "byLabel"){
                var labelBody = document.getElementById("labelBody");

                while (labelBody.firstChild && labelBody.childElementCount > 1) {
                    labelBody.removeChild(labelBody.firstChild);
                }

                if(!hasData){
                    return;
                }

                var tasks = response.tasks;
                var taskLabels = response.task_labels;
                var labels = response.labels;
                tasks.forEach(function(task) {
                    var taskId = task.task_id;
                    var taskTitle = task.title;
                    var row = document.createElement('tr');
                    var userTask = document.createElement('td');
                    var userTaskDiv = document.createElement('div');
                    var taskNameText = document.createElement('div');
    
                    taskNameText.classList.add('task-text-name');
                    userTaskDiv.classList.add("text-container");
                    userTask.classList.add("second-col");
                    userTask.classList.add("clickable");
                    userTask.classList.add("no-select");
                    userTask.classList.add("colored-section");
                    
                    userTask.style.setProperty('--before-bg-color', task.task_color);
                    userTask.id = "task-" + taskId;
                    userTask.addEventListener('click', taskClick, false);
                    taskNameText.textContent = taskTitle;
                    userTaskDiv.appendChild(taskNameText);
                    // Címkék kezelése
                    if (taskLabels && Array.isArray(taskLabels)) {
                        
                        for (var i = 0; i < taskLabels.length; i++) {
                            // Access label properties
                            if(taskLabels[i].task_id == task.task_id){
                                for (var i = 0; i < labels.length; i++) {
                                
                                    if(taskLabels[i].label_id == labels[i].label_id && taskLabels[i].task_id == task.task_id){
                                        var newDiv = document.createElement('div');
                                        var newP = document.createElement('p');
                                        var newImg = document.createElement('img');
                                        newP.textContent = labels[i].label_name;
                                        newImg.src = labels[i].label_symbol;
                                        newDiv.classList.add('ellipse');
                                        newP.classList.add('preview-text');
                                        newImg.classList.add('preview-image');
                                        newDiv.classList.add('clickable');
                                        newDiv.classList.add("no-select");
                                        var rgb = hexToRgb(labels[i].label_color);
        
                                        var rgbString = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
                                        var textColor = getContrastColor(rgbString);
                                        newP.style.color = textColor; 
                                    
                                        if(textColor == 'white'){
                                        newImg.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
                                        }
    
                                        newDiv.style.backgroundColor = labels[i].label_color;
                    
                                        if(labels[i].label_symbol != ""){
                                        newImg.style.display = "inline-block";
                                        }
                                        
                                        newDiv.appendChild(newP);
                                        newDiv.appendChild(newImg);
                    
                                        userTaskDiv.appendChild(newDiv);
                                    }
                                }
                            }
                        }
                    }
    
                    userTask.appendChild(userTaskDiv);
                    row.appendChild(userTask);
                    var newSortTaskByLabelRow = document.getElementById("newSortTaskByLabelRow");
                    var labelBody = document.getElementById("labelBody");
                    labelBody.insertBefore(row, newSortTaskByLabelRow);
                });

                
            } // Folyamat alapú rendezés
            else if(type == 'byProgress'){  

                if(!hasData){
                    return;
                }

                var tasks = response.tasks;
                var taskLabels = response.task_labels;
                var labels = response.labels;
                var task_sorting_row = response.task_sorting_row;

                tasks.forEach(function(task) {
                    var taskId = task.task_id;
                    var taskTitle = task.title;
                    var row = document.createElement('tr');
                    var userTask = document.createElement('td');
                    var userTaskDiv = document.createElement('div');
                    var taskNameText = document.createElement('div');
    
                    taskNameText.classList.add('task-text-name');
                    userTaskDiv.classList.add("text-container");
                    userTask.classList.add("second-col");
                    userTask.classList.add("clickable");
                    userTask.classList.add("no-select");
                    userTask.classList.add("colored-section");
                    
                    userTask.style.setProperty('--before-bg-color', task.task_color);
                    userTask.id = "task-" + taskId;
                    userTask.addEventListener('click', taskClick, false);
                    taskNameText.textContent = taskTitle;
                    userTaskDiv.appendChild(taskNameText);
                    // Címkék kezelése
                    if (taskLabels && Array.isArray(taskLabels)) {
                        
                        for (var i = 0; i < taskLabels.length; i++) {
                            // Access label properties
                            if(taskLabels[i].task_id == task.task_id){
                                for (var i = 0; i < labels.length; i++) {
                                
                                    if(taskLabels[i].label_id == labels[i].label_id && taskLabels[i].task_id == task.task_id){
                                        var newDiv = document.createElement('div');
                                        var newP = document.createElement('p');
                                        var newImg = document.createElement('img');
                                        newP.textContent = labels[i].label_name;
                                        newImg.src = labels[i].label_symbol;
                                        newDiv.classList.add('ellipse');
                                        newP.classList.add('preview-text');
                                        newImg.classList.add('preview-image');
                                        newDiv.classList.add('clickable');
                                        newDiv.classList.add("no-select");
                                        var rgb = hexToRgb(labels[i].label_color);
        
                                        var rgbString = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
                                        var textColor = getContrastColor(rgbString);
                                        newP.style.color = textColor; 
                                    
                                        if(textColor == 'white'){
                                        newImg.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
                                        }
    
                                        newDiv.style.backgroundColor = labels[i].label_color;
                    
                                        if(labels[i].label_symbol != ""){
                                        newImg.style.display = "inline-block";
                                        }
                                        
                                        newDiv.appendChild(newP);
                                        newDiv.appendChild(newImg);
                    
                                        userTaskDiv.appendChild(newDiv);
                                    }
                                }
                            }
                        }
                    }

                    userTask.appendChild(userTaskDiv);
                    row.appendChild(userTask);
                    var found = false;
                    if (task_sorting_row && Array.isArray(task_sorting_row)) {
                        
                        for (var i = 0; i < task_sorting_row.length; i++) {
                            if(task_sorting_row[i].task_id == task.task_id){
                                if(task_sorting_row[i].by_progress == 'to_do'){
                                    var toDoBody = document.getElementById("toDoBody");
                                    toDoBody.appendChild(row);
                                    found = true;
                                }
                                else if(task_sorting_row[i].by_progress == 'in_progress'){
                                    var inProgressBody = document.getElementById("inProgressBody");
                                    inProgressBody.appendChild(row);
                                    found = true;
                                }
                                else if(task_sorting_row[i].by_progress == 'done'){
                                    var doneBody = document.getElementById("doneBody");
                                    doneBody.appendChild(row);
                                    found = true;
                                }
                                else{
                                    var newSortByProgressRow = document.getElementById("newSortByProgressRow");
                                    var byProgressBody = document.getElementById("byProgressBody");
                                    byProgressBody.insertBefore(row, newSortByProgressRow);
                                    found = true;
                                }
                                }
                            }
                        }
                        
                        if(!found){
                            var newSortByProgressRow = document.getElementById("newSortByProgressRow");
                            var byProgressBody = document.getElementById("byProgressBody");
                            byProgressBody.insertBefore(row, newSortByProgressRow);
                        }


                   
                });
               
            }   // Rendszerezés Eisenhover mátrix-szal
            else if(type == "eisenhover"){
                var eisenhoverBody = document.getElementById("eisenhoverBody");
                if(!hasData){
                    return;
                }

                var tasks = response.tasks;
                var taskLabels = response.task_labels;
                var labels = response.labels;
                var task_sorting_row = response.task_sorting_row;

                tasks.forEach(function(task) {
                    var taskId = task.task_id;
                    var taskTitle = task.title;
                    var row = document.createElement('tr');
                    var userTask = document.createElement('td');
                    var userTaskDiv = document.createElement('div');
                    var taskNameText = document.createElement('div');
    
                    taskNameText.classList.add('task-text-name');
                    userTaskDiv.classList.add("text-container");
                    userTask.classList.add("second-col");
                    userTask.classList.add("clickable");
                    userTask.classList.add("no-select");
                    userTask.classList.add("colored-section");
                    
                    userTask.style.setProperty('--before-bg-color', task.task_color);
                    userTask.id = "task-" + taskId;
                    userTask.addEventListener('click', taskClick, false);
                    taskNameText.textContent = taskTitle;
                    userTaskDiv.appendChild(taskNameText);
                    // Címkék kezelése
                    if (taskLabels && Array.isArray(taskLabels)) {
                        
                        for (var i = 0; i < taskLabels.length; i++) {
                            // Access label properties
                            if(taskLabels[i].task_id == task.task_id){
                                for (var i = 0; i < labels.length; i++) {
                                
                                    if(taskLabels[i].label_id == labels[i].label_id && taskLabels[i].task_id == task.task_id){
                                        var newDiv = document.createElement('div');
                                        var newP = document.createElement('p');
                                        var newImg = document.createElement('img');
                                        newP.textContent = labels[i].label_name;
                                        newImg.src = labels[i].label_symbol;
                                        newDiv.classList.add('ellipse');
                                        newP.classList.add('preview-text');
                                        newImg.classList.add('preview-image');
                                        newDiv.classList.add('clickable');
                                        newDiv.classList.add("no-select");
                                        var rgb = hexToRgb(labels[i].label_color);
        
                                        var rgbString = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
                                        var textColor = getContrastColor(rgbString);
                                        newP.style.color = textColor; 
                                    
                                        if(textColor == 'white'){
                                        newImg.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
                                        }
    
                                        newDiv.style.backgroundColor = labels[i].label_color;
                    
                                        if(labels[i].label_symbol != ""){
                                        newImg.style.display = "inline-block";
                                        }
                                        
                                        newDiv.appendChild(newP);
                                        newDiv.appendChild(newImg);
                    
                                        userTaskDiv.appendChild(newDiv);
                                    }
                                }
                            }
                        }
                    }
                    userTask.appendChild(userTaskDiv);
                    row.appendChild(userTask);
                    var found = false;
                    console.log(task_sorting_row);
                    if (task_sorting_row && Array.isArray(task_sorting_row)) {
                        
                        for (var i = 0; i < task_sorting_row.length; i++) {
                            if(task_sorting_row[i].task_id == task.task_id){
                                if(task_sorting_row[i].eisenhover == 'urgent_important'){
                                    console.log("asd");
                                    var urgImp = document.getElementById("urgImp");
                                    urgImp.appendChild(row);
                                    found = true;
                                }
                                else if(task_sorting_row[i].eisenhover == 'urgent_not_important'){
                                    var urgNotImp = document.getElementById("urgNotImp");
                                    urgNotImp.appendChild(row);
                                    found = true;
                                }
                                else if(task_sorting_row[i].eisenhover == 'not_urgent_important'){
                                    var notUrgImp = document.getElementById("notUrgImp");
                                    notUrgImp.appendChild(row);
                                    found = true;
                                }
                                else if(task_sorting_row[i].eisenhover == 'not_urgent_not_important'){
                                    var notUrgNotImp = document.getElementById("notUrgNotImp");
                                    notUrgNotImp.appendChild(row);
                                    found = true;
                                }
                                else{
                                    var newEisenhoverRow = document.getElementById("newEisenhoverRow");
                                    var eisenhoverBody = document.getElementById("eisenhoverBody");
                                    eisenhoverBody.insertBefore(row, newEisenhoverRow);
                                    found = true;
                                }
                                }
                            }
                        }
                        if(!found){
                            var newEisenhoverRow = document.getElementById("newEisenhoverRow");
                        var eisenhoverBody = document.getElementById("eisenhoverBody");
                        eisenhoverBody.insertBefore(row, newEisenhoverRow);
                        }   
                        
                        
                });
            }
            else if(type == "teacher"){
                var groupTasksBody = document.getElementById("groupTasksBody");
             while (groupTasksBody.firstChild && groupTasksBody.childElementCount > 2) {
                groupTasksBody.removeChild(groupTasksBody.children[1]);
            }

            if(!hasData){
                return;
            }
            var tasks = response.tasks;

            tasks.sort(function (a, b) {
                var startTimeComparison = a.start_time.localeCompare(b.start_time);
            
                if (startTimeComparison === 0) {
                    return a.end_time.localeCompare(b.end_time);
                }

                return startTimeComparison;
            });

            var taskLabels = response.task_labels;
            var labels = response.labels;
            tasks.forEach(function(task) {
                var taskId = task.task_id;
                var taskTitle = task.title;
                var row = document.createElement('tr');
                var userTask = document.createElement('td');
                var userTaskDiv = document.createElement('div');

                var taskNameText = document.createElement('div');

                taskNameText.classList.add('task-text-name');

               // userTaskDiv.textContent = taskTitle;
                userTaskDiv.classList.add("text-container");
                userTask.classList.add("second-col");
                userTask.classList.add("clickable");
                userTask.classList.add("no-select");
                userTask.classList.add("colored-section");
                
                userTask.style.setProperty('--before-bg-color', task.task_color);
                userTask.id = "task-" + taskId;
                userTask.addEventListener('click', taskClick, false);
                taskNameText.textContent = taskTitle;
                userTaskDiv.appendChild(taskNameText);
                // Címkék kezelése
                if (taskLabels && Array.isArray(taskLabels)) {
                    
                    for (var i = 0; i < taskLabels.length; i++) {
                        // Access label properties
                        if(taskLabels[i].task_id == task.task_id){
                            for (var i = 0; i < labels.length; i++) {
                            
                                if(taskLabels[i].label_id == labels[i].label_id && taskLabels[i].task_id == task.task_id){
                                    var newDiv = document.createElement('div');
                                    var newP = document.createElement('p');
                                    var newImg = document.createElement('img');
                                    newP.textContent = labels[i].label_name;
                                    newImg.src = labels[i].label_symbol;
                                    newDiv.classList.add('ellipse');
                                    newP.classList.add('preview-text');
                                    newImg.classList.add('preview-image');
                                    newDiv.classList.add('clickable');
                                    newDiv.classList.add("no-select");
                                    var rgb = hexToRgb(labels[i].label_color);
    
                                    var rgbString = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
                                    var textColor = getContrastColor(rgbString);
                                    newP.style.color = textColor; 
                                
                                    if(textColor == 'white'){
                                    newImg.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
                                    }

                                    newDiv.style.backgroundColor = labels[i].label_color;
                
                                    if(labels[i].label_symbol != ""){
                                    newImg.style.display = "inline-block";
                                    }
                                    
                                    newDiv.appendChild(newP);
                                    newDiv.appendChild(newImg);
                
                                    userTaskDiv.appendChild(newDiv);
                                }
                            }
                        }
                    }
                }

                userTask.appendChild(userTaskDiv);
                row.appendChild(userTask);
                var newGroupTaskRow = document.getElementById("newGroupTaskRow");
                var groupTasksBody = document.getElementById("groupTasksBody");
                groupTasksBody.insertBefore(row, newGroupTaskRow);
            });
            }
            else{ // Type is not defined
                return;
            }


           

            
}


function listTasks(date){
    $.ajax({
        type: 'POST',
        url: 'task_query.php', 
        dataType: "json",
        data: {'date': date},
        credentials: 'same-origin',
        success: function(response) {
            fillTaskTable(response, "daily");
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}