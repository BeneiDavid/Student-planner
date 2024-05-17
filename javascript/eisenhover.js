$(document).ready(function() {

    $(function() {
        $(".t_sortable").sortable({
          helper: function(event, ui) {
            ui.children().each(function() {
              $(this).width($(this).width()); // Set width of dragged item to match original row's width
            });
            return ui;
          }
        });
      });


    $("tbody.t_sortable").sortable({
      connectWith: ".t_sortable",
      items: "> tr:not(:first):not(.no-drag-drop):not(.no-drag-drop)",
      helper:"clone",
      zIndex: 999990,
      receive: function(event, ui) {
            var movedRow = ui.item;
            // Get the ID of the table
            var tableId = $(this).closest('.selected-day-div').attr('id');
            // Do something with the table ID and the moved row
            console.log("Row moved to table with ID:", tableId);
            
            const htmlString = movedRow.html();
            const regex = /id="([^"]+)"/;
                const match = htmlString.match(regex);

            if (match) {
                const tdId = match[1];
                //sortTaskByProgress(tdId, tableId);
                sortTaskByEisenhover(tdId, tableId);
                // Save data to database

            } else {
                console.log("ID attribute not found");
            }

      }

  }).disableSelection();
});



function sortTaskByEisenhover(tdId, tableId){
    const id = tdId.split('-')[1];
    $.ajax({
        type: 'POST',
        url: 'queries/sort_by_eisenhover_query.php', 
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

function refreshEisenhoverTasks(){
    clearEisenhoverTasks();
    clearUrgentImportant();
    clearUrgentNotImportant();
    clearNotUrgentImportant();
    clearNotUrgentNotImportant();
    fillEisenhoverTasks();
}

function clearEisenhoverTasks(){
    var eisenhoverBody = document.getElementById("eisenhoverBody");
    var children = eisenhoverBody.children;
    var childrenCount = children.length;
    // Start loop from the third child and iterate up to the second last child
    for (var i = childrenCount - 2; i >= 2; i--) {
        eisenhoverBody.removeChild(children[i]);
    }
}

function clearUrgentImportant(){
    var urgImp = document.getElementById("urgImp");
    var children = urgImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        urgImp.removeChild(children[i]);
    }
}

function clearUrgentNotImportant(){
    var urgNotImp = document.getElementById("urgNotImp");
    var children = urgNotImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        urgNotImp.removeChild(children[i]);
    }
}

function clearNotUrgentImportant(){
    var notUrgImp = document.getElementById("notUrgImp");
    var children = notUrgImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        notUrgImp.removeChild(children[i]);
    }
}

function clearNotUrgentNotImportant(){
    var notUrgNotImp = document.getElementById("notUrgNotImp");
    var children = notUrgNotImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        notUrgNotImp.removeChild(children[i]);
    }
}

function fillEisenhoverTasks(){
    $.ajax({
        type: 'POST',
        url: 'queries/task_query.php', 
        data: {},
        credentials: 'same-origin',
        success: function(response) {
            console.log(response);
            if(response.length == 0){
              return;
            } 
            else{
              fillTaskTable(response, "eisenhover");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function init(){
    fillEisenhoverTasks();
}


window.addEventListener('load', init, false);