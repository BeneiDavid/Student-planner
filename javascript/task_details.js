// Feladat beállítás modal megjelenítése és beállítása
async function addTask(event, taskDetails) {
    resetTask();
    hideAddLabelModal();
    hideNewLabelModal();
    hideDeleteTaskModal();
    hideTaskNameError();
    hideDateError();
    var img = setAndGetAddLabelImage();
    var existingTask = document.getElementById('existingTask');
    var deleteTaskButton = document.getElementById('deleteTask');
    // Egy már létező feladat adatai
    if (typeof taskDetails !== 'undefined' && taskDetails != "") {
        var userId = await getCurrentUserId();
        var data = JSON.parse(taskDetails);
        var task = data.task_details[0];
        setTaskName(task.title);
        setTaskColor(task.task_color);
        setDescription(task.task_description);
        setExistingDate(task.date);

        if(task.start_time_enabled == "1"){
            setAndEnableStartTime(task.start_time);
        }
    
        if(task.end_time_enabled == "1"){
            setAndEnableEndTime(task.end_time);
        }

        // A felhasználó által létrehozott feladat
        if(task.user_id == userId){
            fillAddedLabels(img, data.label_details);
            existingTask.value = task.task_id;
            deleteTaskButton.style.visibility = 'visible';
            enableStartTime();
            enableEndTime();
            showCheckboxes();
            enableFields();
           
            showSaveButton();
        }
        // Csoportfeladat
        else{ 
            deleteTaskButton.style.visibility = 'hidden';
            img = null;
            fillAddedLabels(img, data.label_details);
            disableFields();
            var groupName = await getTaskGroupName(task.task_id);
            setGroupName(groupName);
            hideCheckboxes();
            hideSaveButton();
        }
    }
    else{ // Új feladat adatai
        setTaskColor("#0000FF");
        setDate();
        fillAddedLabels(img);
        existingTask.value = -1; 
        deleteTaskButton.style.visibility = 'hidden';
        enableStartTime();
        enableEndTime();
        showCheckboxes();
        enableFields();
        showSaveButton();
    }

    $('#taskModal').modal('show');
}

// Módosítható mezők letiltása
function disableFields(){
    document.getElementById('taskname').disabled = true;
    document.getElementById('colorpicker').disabled = true;
    document.getElementById('date').disabled = true;
    document.getElementById('startTime').disabled = true;
    document.getElementById('endTime').disabled = true;
    document.getElementById('taskDescription').disabled = true;
} 

// Módosítható mezők engedélyezése 
function enableFields(){
    document.getElementById('taskname').disabled = false;
    document.getElementById('colorpicker').disabled = false;
    document.getElementById('date').disabled = false;
    document.getElementById('taskDescription').disabled = false;
} 

// Checkboxok elrejtése
function hideCheckboxes(){
    document.getElementById('enableStartTime').style.display = "none";
    document.getElementById('enableEndTime').style.display = "none";
}

// Checkboxok megjelenítése
function showCheckboxes(){
    document.getElementById('enableStartTime').style.display = "inline-block";
    document.getElementById('enableEndTime').style.display = "inline-block";
}

// Mentés gomb elrejtése
function hideSaveButton(){
    document.getElementById('saveTaskButton').style.display = "none";
}

// Mentés gomb megjelenítése
function showSaveButton(){
    document.getElementById('saveTaskButton').style.display = "inline-block";
}

// Címkék hozzádása ablak feltöltése címkékkel az új feladathoz
function fillAddedLabels(img){
    var added_labels = document.getElementById("added_labels");
    added_labels.appendChild(img);
}

// Címkék hozzádása ablak feltöltése címkékkel a létező feladathoz
function fillAddedLabels(img, labels){
    var added_labels = document.getElementById("added_labels");

    if (typeof labels !== 'undefined' && labels != "") {
        labels.forEach(label => {

            var newDiv = document.createElement('div');
            var newP = document.createElement('p');
            var newImg = document.createElement('img');

            newP.textContent = label.label_name;
            newImg.src = label.label_symbol;
            newDiv.classList.add('ellipse');
            newP.classList.add('preview-text');
            newImg.classList.add('preview-image');
            newImg.title = "Címke ikon";
            newDiv.classList.add('no-select');
            
            var rgb = hexToRgb(label.label_color);
            var rgbString = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
            var textColor = getContrastColor(rgbString);
            newP.style.color = textColor; 

            if(textColor == 'white'){
              newImg.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
            }

            newDiv.style.backgroundColor = label.label_color;
            newDiv.id = 'copy_' + label.label_id;

            if(label.label_symbol != ""){
              newImg.style.display = "inline-block";
            }
            
            newDiv.appendChild(newP);
            newDiv.appendChild(newImg);

            added_labels.appendChild(newDiv);
        });
    }

    if(img){
        added_labels.appendChild(img);
    }
    
}

// Létező feladat dátumának beállítása
function setExistingDate(date){
    var taskDate = document.getElementById('date');
    taskDate.value = date;
}

// Feladat leírás beállítása
function setDescription(description){
    var taskDescription = document.getElementById('taskDescription');
    taskDescription.value = description;
}

// Kezdő idő beállítása és engedélyezése
function setAndEnableStartTime(time){
    var startTime = document.getElementById('startTime');
    var enableStart = document.getElementById('enableStartTime');
    startTime.value = time;
    enableStart.checked = true;
}

// Befejező idő beállítása és engedélyezése
function setAndEnableEndTime(time){
    var endTime = document.getElementById('endTime');
    var enableEnd = document.getElementById('enableEndTime');
    endTime.value = time;
    enableEnd.checked = true;
}

// Feladatnév beállítása
function setTaskName(text){
    var taskname = document.getElementById('taskname');
    taskname.value = text;
}

// Csoport név kiürítése
function clearGroupName(){
    var groupNameSpan = document.getElementById('groupNameSpan');
    groupNameSpan.textContent = "";
}

// Csoport név beállítása
function setGroupName(groupName){
    var groupNameSpan = document.getElementById('groupNameSpan');
    groupNameSpan.textContent = " - Csoport: " + groupName;
}
    
// Feladat modal alaphelyzetbe állítása
function resetTask(){
    clearTaskName();
    clearTimeFields();
    clearDescription();
    uncheckTimeCheckboxes();
    clearDate();
    clearGroupName();
}

// Dátum kiürítése
function clearDate(){
    var date = document.getElementById('date');
    date.value = "";
}

// Kezdési idő input engedélyezése
function enableStartTime(){
    var startCheckbox = document.getElementById('enableStartTime');
    const startTimeInput = document.getElementById("startTime");

    if(startCheckbox.checked){
      startTimeInput.disabled = false;
    }
    else{
      startTimeInput.disabled = true;
    } 
}
  
// Befejező idő input engedélyezése
function enableEndTime(){
    endCheckbox = document.getElementById('enableEndTime');
    const endTimeInput = document.getElementById("endTime");

    if(endCheckbox.checked){
      endTimeInput.disabled = false;
    }
    else{
      endTimeInput.disabled = true;
    } 
}

// Feladat módosítás mentése
async function saveTaskButton(event){
    var addLabelModal = document.getElementById("addLabelModal");  
    var newLabel_modal = document.getElementById("newLabelModal");
    var deleteTaskModal = document.getElementById("deleteTaskModal");
  
    if(addLabelModal.style.display == "block" || newLabel_modal.style.display == "block" || deleteTaskModal.style.display == "block"){
      return;
    }
  
    var taskName = document.getElementById('taskname');
    var colorpicker = document.getElementById('colorpicker');
    var addedLabels = document.getElementById('added_labels');
    var date = document.getElementById('date');
    var startTime = document.getElementById('startTime');
    var endTime = document.getElementById('endTime');
    var enableStartTime = document.getElementById('enableStartTime');
    var enableEndTime = document.getElementById('enableEndTime');
    var taskDescription = document.getElementById('taskDescription');
    var idList = [];
  
    hidePreviousTaskErrors();
  
    event.preventDefault();
  
    if(!showTaskSubmitErrors(date, startTime, endTime, enableStartTime, enableEndTime, taskName)){
      return;
    }
  
    var labels = addedLabels.querySelectorAll('div');
    
    labels.forEach(function(div) {
       var parts = div.id.split('_');
       var number = parseInt(parts[parts.length - 1]);
       idList.push(number);
  
    });
  
    var jsonIdList = JSON.stringify(idList);
  
    var taskAddData = $(this).serialize();
  
    var existingTask = document.getElementById('existingTask');

    // Létező feladat módosítása
    if(existingTask.value != -1){
        await $.ajax({
            type: 'POST',
            url: 'queries/task_details_update_query.php', 
            data: {'taskAddData': taskAddData,
                  'taskId': existingTask.value, 
                  'taskName': taskName.value,
                  'colorpicker': colorpicker.value,
                  'jsonIdList': jsonIdList,
                  'date': date.value,
                  'startTime': startTime.value,
                  'endTime': endTime.value,
                  'enableStartTime': enableStartTime.checked,
                  'enableEndTime': enableEndTime.checked,
                  'taskDescription': taskDescription.value
            },
            credentials: 'same-origin',
            success: function(response) {
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    else{ // Új feladat létrehozása
        await $.ajax({
            type: 'POST',
            url: 'modals/task_details_modal.php', 
            data: {'taskAddData': taskAddData,
                'taskName': taskName.value,
                'colorpicker': colorpicker.value,
                'jsonIdList': jsonIdList,
                'date': date.value,
                'startTime': startTime.value,
                'endTime': endTime.value,
                'enableStartTime': enableStartTime.checked,
                'enableEndTime': enableEndTime.checked,
                'taskDescription': taskDescription.value
            },
            credentials: 'same-origin',
            success: function(response) {
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    // Feladatok frissítése - napi megjelenítése
    if(document.getElementById('selectedDate')){
        refreshDayTasks();
    }
    // Feladatok frissítése - címke szerinti rendezés
    if(document.getElementById('labelBody')){
        refreshSortByLabelTasks();
    }
    // Feladatok frissítése - heti megjelenítése
    if(document.getElementById('selectedWeekDate')){
        refreshWeeklyDisplay();
    }
    // Feladatok frissítése - havi megjelenítése
    if(document.getElementById('selectedMonthDate')){
        refreshMonthlyDisplay();
    }

    $('#taskModal').modal('hide');
}

// Feladat leírás törlése
function clearDescription(){
    var taskDescription = document.getElementById('taskDescription');
    taskDescription.value = "";
}
  
// Feladat név törlése
function clearTaskName(){
    var taskname = document.getElementById('taskname');
    taskname.value = "";
}
  
// Feladat modal idő törlése
function clearTimeFields(){
    var startTime = document.getElementById('startTime');
    startTime.value = '';
    var endTime = document.getElementById('endTime');
    endTime.value = '';
}

// Dátum beállítása
function setDate(){
    var fullDate = "";
    var selectedDate = "";

    // Napi megjelenítés esetén
    if(document.getElementById('selectedDate')){
        selectedDate = document.getElementById('selectedDate').value;  
    } // Heti megjelenítés esetén
    else if(document.getElementById('selectedWeekDate')){
        selectedDate = document.getElementById('selectedWeekDate').value;
    }
    else{
        return;
    }
    
    var dateComponents = selectedDate.split('-');
    var year = dateComponents[0];
    var month = ("0" + dateComponents[1]).slice(-2);
    var day = dateComponents[2];

    if(day < 10){
        fullDate = year + "-" + month + "-0" + day;
    }
    else{
        fullDate = year + "-" + month + "-" + day;
    }

    document.getElementById("date").value = fullDate;
}
  
// Feladatszín beállítása
function setTaskColor(color){
document.getElementById('colorpicker').value = color;
}

// Kezdési idő checkbox kezelése
function startTimeChanged(){
const startTimeInput = document.getElementById("startTime");
if (this.checked) {
    startTimeInput.disabled = false;
}
else{
    startTimeInput.disabled = true;
}
}

// Befejező idő checkbox kezelése
function endTimeChanged(){
const endTimeInput = document.getElementById("endTime");
if (this.checked) {
    endTimeInput.disabled = false;
}
else{
    endTimeInput.disabled = true;
}
}

// Idő checkboxok legyenek üresek
function uncheckTimeCheckboxes(){
    var enableStartTime = document.getElementById('enableStartTime');
    enableStartTime.checked = false;
  
    var enableEndTime = document.getElementById('enableEndTime');
    enableEndTime.checked = false;
}
  
// Címke hozzáadása gomb (kép) beállítása és visszaadása
function setAndGetAddLabelImage(){
    var img = document.createElement("img");
    img.src = "pictures/plus-circle.svg";
    img.style.marginLeft = "10px";
    img.classList.add('clickable');
    img.addEventListener('click', addLabelModalClick);
    img.onmouseover = function() {
        this.src = 'pictures/image.svg'; 
    };

    img.onmouseleave = function() {
        this.src = "pictures/plus-circle.svg"; 
    };

    return img;
}

// Dátum hiba megjelenítése
function showDateError(){
    var dateError = document.getElementById("dateError");
    dateError.style.display = "inline-block";
}
  
// Kezdési idó hiba megjelenítése
function showStartTimeError(){
    var startTimeError = document.getElementById("startTimeError");
    startTimeError.style.display = "inline-block";
}

// Befejező idó hiba megjelenítése
function showEndTimeError(){
    var endTimeError = document.getElementById("endTimeError");
    endTimeError.style.display = "inline-block";
}

// Feladat név hiba megjelenítése
function showTaskNameError(){
    var taskNameError = document.getElementById("taskNameError");
    taskNameError.style.display = "block";
}

// Idő hiba megjelenítése
function showTimeValueError(){
    var taskValueError = document.getElementById("timeValueError");
    taskValueError.style.display = "inline-block";
}

// Dátum hiba elrejtése
function hideDateError(){
    var dateError = document.getElementById("dateError");
    dateError.style.display = "none";
}

// Kezdési idó hiba elrejtése
function hideStartTimeError(){
    var startTimeError = document.getElementById("startTimeError");
    startTimeError.style.display = "none";
}

// Befejező idó hiba elrejtése
function hideEndTimeError(){
    var endTimeError = document.getElementById("endTimeError");
    endTimeError.style.display = "none";
}

// Feladat név hiba elrejtése
function hideTaskNameError(){
    var taskNameError = document.getElementById("taskNameError");
    taskNameError.style.display = "none";
}

// Idő hiba elrejtése
function hideTimeValueError(){
    var taskValueError = document.getElementById("timeValueError");
    taskValueError.style.display = "none";
}

// Előző feladat módosítás hibák elrejtése
function hidePreviousTaskErrors(){
    hideDateError();
    hideStartTimeError();
    hideEndTimeError();
    hideTaskNameError();
    hideTimeValueError();
}

// Feladat módosítás hibák kiírása
function showTaskSubmitErrors(date, startTime, endTime, enableStartTime, enableEndTime, taskName){
    var canSubmit = true;

    if (date.value == '') {
        showDateError();
        canSubmit = false;
    }

    if (enableStartTime.checked == true && startTime.value == '') {
        showStartTimeError();
        canSubmit = false;
    }

    if (enableEndTime.checked == true && endTime.value == '') {
        showEndTimeError();
        canSubmit = false;
    }

    if(taskName.value.length === 0){
        showTaskNameError();
        canSubmit = false;
    }

    if(enableStartTime.checked == true && enableEndTime.checked == true && startTime.value > endTime.value){
        showTimeValueError();
        canSubmit = false;
    }

    return canSubmit;
}

// Feladat törlése
function deleteTask(){
    var addLabelModal = document.getElementById("addLabelModal");
    var newLabel_modal = document.getElementById("newLabelModal");

    if(addLabelModal.style.display == "block" || newLabel_modal.style.display == "block"){
        return;
    }

    showDeleteTaskModal();
}

// Feladat törlés modal bezárása
function closeDeleteTask(){
    hideDeleteTaskModal();
}

// Feladat törlés elrejtése
function hideDeleteTaskModal(){
    var deleteTaskModal = document.getElementById("deleteTaskModal");
    deleteTaskModal.style.display = "none";
}

// Feladat törlése modal megjelenítése
function showDeleteTaskModal(){
    var deleteTaskModal = document.getElementById("deleteTaskModal");
    var modalDiv = document.getElementById("modal_div");
    modalDiv.appendChild(deleteTaskModal);
    deleteTaskModal.style.display = "block";
}

// Törlés megerősítése
function confirmDelete(event){
    var taskId = document.getElementById('existingTask').value;
    var taskAddData = $(this).serialize();
    event.preventDefault();
    
    $.ajax({
        type: 'POST',
        url: 'modals/delete_task_modal.php', 
        data: {'taskAddData': taskAddData,
              'taskId': taskId
        },
        success: function(response) {
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

    // Napi megjelenítés
    if(document.getElementById('selectedDate')){
        refreshDayTasks();
    }
    // Címke szerinte rendezés
    if(document.getElementById('labelBody')){
        refreshSortByLabelTasks();
    } 
    // Heti megjelenítés
    if(document.getElementById('selectedWeekDate')){
        refreshWeeklyDisplay();
    }
    // Havi megjelenítés
    if(document.getElementById('selectedMonthDate')){
        refreshMonthlyDisplay();
    }

    $('#taskModal').modal('hide');
}

// Napi feladatok frissítése
function refreshDayTasks(){
    var selectedDate = document.getElementById('selectedDate').value;
    var dateComponents = selectedDate.split('-');
    var year = dateComponents[0];
    var month = dateComponents[1];
    var day = dateComponents[2];
    var fullDate = year + "-" + month + "-" + day;
    listTasks(fullDate);
    refreshCalendarDots();
}

// Inicializálás
function init(){
    if (document.getElementById('add-task-button')) {
        document.getElementById('add-task-button').addEventListener('click', addTask, false);
    }
    if (document.getElementById('enableStartTime')) {
        document.getElementById('enableStartTime').addEventListener('change', startTimeChanged, false);
    }
    if (document.getElementById('enableEndTime')) {
        document.getElementById('enableEndTime').addEventListener('change', endTimeChanged, false);
    }
    if (document.getElementById('saveTaskButton')) {
    document.getElementById('saveTaskButton').addEventListener('click', saveTaskButton, false);
    }
    if (document.getElementById('deleteTask')) {
    document.getElementById('deleteTask').addEventListener('click', deleteTask, false);
    }
    if (document.getElementById('taskDelete_cancelButton')) {
    document.getElementById('taskDelete_cancelButton').addEventListener('click', closeDeleteTask, false);
    }
    if (document.getElementById('taskDelete_xButton')) {
    document.getElementById('taskDelete_xButton').addEventListener('click', closeDeleteTask, false);
    }
    if (document.getElementById('confirmDelete')) {
    document.getElementById('confirmDelete').addEventListener('click', confirmDelete, false);
    }
    
    $('#taskModal').off('hide.bs.modal').on('hide.bs.modal', function () {
        var added_labels = document.getElementById("added_labels");
        added_labels.innerHTML = '';
        // Napi megjelenítés
        if(document.getElementById('selectedDate')){
          refreshDayTasks();
        }
        if(document.getElementById('labelBody')){
            refreshSortByLabelTasks();
        }
        if(document.getElementById('byProgressBody')){
            refreshSortByProgressTasks();
        }
        if(document.getElementById('eisenhowerBody')){
            refreshEisenhowerTasks();
        }
        if(document.getElementById('groupTasksBody') && document.getElementById('createNewGroup')){
            refreshGroupTasks();
        }
       
        });


    
}

// Címke hozzáadás, módosítás modalok bezárása kikattintáskor
window.onclick = function(event) {

    var addLabelModal = document.getElementById("addLabelModal");

    if (event.target == addLabelModal) {
      addLabelModal.style.display = "none";
    }
    
    var newLabel_modal = document.getElementById("newLabelModal");

    if (event.target == newLabel_modal) {
      newLabel_modal.style.display = "none";
      addLabelModal.style.display = "block";
      hideLabelNameError();
      resetNewLabelModal();
    }

    var deleteTaskModal = document.getElementById("deleteTaskModal");

    if (event.target == deleteTaskModal) {
        deleteTaskModal.style.display = "none";
    }

    var sortByLabelModal = document.getElementById("sortByLabelModal");
    if (event.target == sortByLabelModal) {
      sortByLabelModal.style.display = "none";
    }

    var addMembersModal = document.getElementById("addMembersModal");
    if (event.target == addMembersModal) {
        addMembersModal.style.display = "none";
    }

    var groupDeleteConfirmModal = document.getElementById("groupDeleteConfirmModal");
    if (event.target == groupDeleteConfirmModal) {
        groupDeleteConfirmModal.style.display = "none";
    }

}

window.addEventListener('load', init, false);





