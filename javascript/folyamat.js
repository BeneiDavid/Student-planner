// Táblázatok celláinak mozgatása
$(document).ready(function() {
    var $tables = $('.tables_ui') 
    $("tbody.t_sortable").sortable({
      connectWith: ".t_sortable",
      items: "> tr:not(:first):not(.no-drag-drop):not(.no-drag-drop)",
      helper:"clone",
      zIndex: 999990,
      receive: function(event, ui) {
            var movedRow = ui.item;
            var tableId = $(this).closest('.tables_ui').attr('id');   
            const htmlString = movedRow.html();
            const regex = /id="([^"]+)"/;
            const match = htmlString.match(regex);

            if (match) {
                const tdId = match[1];
                sortTaskByProgress(tdId, tableId);
            } else {
                console.error("ID attribute not found");
            }
      }

  }).disableSelection();
});

// Táblázatok feltöltése
function fillSortByProgressTasks(){
    $.ajax({
        type: 'POST',
        url: 'queries/task_query.php', 
        data: {},
        credentials: 'same-origin',
        success: function(response) {
            if(response.length == 0){
              return;
            } 
            else{
              fillTaskTable(response, "byProgress");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Folyamat szerinti rendezés mentése
function sortTaskByProgress(tdId, tableId){
    const id = tdId.split('-')[1];
    $.ajax({
        type: 'POST',
        url: 'queries/sort_by_progress_query.php', 
        data: {
            'taskId': id,
            'tableId': tableId
        },
        credentials: 'same-origin',
        success: function(response) {
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Feladatok frissítése
function refreshSortByProgressTasks(){
    clearProgressTasks();
    clearToDoTasks();
    clearInProgressTasks();
    clearDoneTasks();
    fillSortByProgressTasks();
}

// Táblázatok kiürítése
function clearProgressTasks(){
    var byProgressBody = document.getElementById("byProgressBody");
    var children = byProgressBody.children;
    var childrenCount = children.length;
    for (var i = childrenCount - 2; i >= 2; i--) {
        byProgressBody.removeChild(children[i]);
    }
}

// Teendők táblázat kiürítése
function clearToDoTasks(){
    var toDoBody = document.getElementById("toDoBody");
    var children = toDoBody.children;

    for (var i = children.length - 2; i >= 2; i--) {
        toDoBody.removeChild(children[i]);
    }
}

// Folyamatban táblázat kiürítése
function clearInProgressTasks(){
    var inProgressBody = document.getElementById("inProgressBody");
    var children = inProgressBody.children;

    for (var i = children.length - 2; i >= 2; i--) {
        inProgressBody.removeChild(children[i]);
    }
}

// Befejezve táblázat kiürítése
function clearDoneTasks(){
    var doneBody = document.getElementById("doneBody");
    var children = doneBody.children;

    for (var i = children.length - 2; i >= 2; i--) {
        doneBody.removeChild(children[i]);
    }
}

// Inicializálás
function init(){
    fillSortByProgressTasks();
}

window.addEventListener('load', init, false);