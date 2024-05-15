$(document).ready(function() {
    var $tables = $('.tables_ui') 
    $("tbody.t_sortable").sortable({
      connectWith: ".t_sortable",
      items: "> tr:not(:first):not(.no-drag-drop):not(.no-drag-drop)",
      helper:"clone",
      zIndex: 999990,
      receive: function(event, ui) {
            var movedRow = ui.item;
            // Get the ID of the table
            var tableId = $(this).closest('.tables_ui').attr('id');
            // Do something with the table ID and the moved row
            console.log("Row moved to table with ID:", tableId);
            
            const htmlString = movedRow.html();
            const regex = /id="([^"]+)"/;
                const match = htmlString.match(regex);

            if (match) {
                console.log("asd");
                const tdId = match[1];
                sortTaskByProgress(tdId, tableId);
            } else {
                console.log("ID attribute not found");
            }

      }

  }).disableSelection();
});

function fillSortByProgressTasks(){
    $.ajax({
        type: 'POST',
        url: 'task_query.php', 
        data: {},
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
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


function sortTaskByProgress(tdId, tableId){
    const id = tdId.split('-')[1];
    $.ajax({
        type: 'POST',
        url: 'sort_by_progress_query.php', 
        data: {
            'taskId': id,
            'tableId': tableId
        },
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function refreshSortByProgressTasks(){
    clearProgressTasks();
    clearToDoTasks();
    clearInProgressTasks();
    clearDoneTasks();
    fillSortByProgressTasks();
}

function clearProgressTasks(){
    var byProgressBody = document.getElementById("byProgressBody");
    console.log(byProgressBody);
    var children = byProgressBody.children;
    var childrenCount = children.length;
    // Start loop from the third child and iterate up to the second last child
    for (var i = childrenCount - 2; i >= 2; i--) {
        byProgressBody.removeChild(children[i]);
    }
}

function clearToDoTasks(){
    var toDoBody = document.getElementById("toDoBody");
    var children = toDoBody.children;

    for (var i = children.length - 1; i >= 1; i--) {
        toDoBody.removeChild(children[i]);
    }
}

function clearInProgressTasks(){
    var inProgressBody = document.getElementById("inProgressBody");
    var children = inProgressBody.children;

    for (var i = children.length - 1; i >= 1; i--) {
        inProgressBody.removeChild(children[i]);
    }
}

function clearDoneTasks(){
    var doneBody = document.getElementById("doneBody");

    var children = doneBody.children;

    for (var i = children.length - 1; i >= 1; i--) {
        doneBody.removeChild(children[i]);
    }
}


function init(){
    fillSortByProgressTasks();
}


window.addEventListener('load', init, false);