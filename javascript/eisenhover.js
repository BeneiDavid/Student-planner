// Táblázatok celláinak mozgatása
$(document).ready(function() {

    $(function() {
        $(".t_sortable").sortable({
          helper: function(event, ui) {
            ui.children().each(function() {
              $(this).width($(this).width());
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
            var tableId = $(this).closest('.selected-day-div').attr('id');
            
            const htmlString = movedRow.html();
            const regex = /id="([^"]+)"/;
                const match = htmlString.match(regex);

            if (match) {
                const tdId = match[1];
                sortTaskByEisenhover(tdId, tableId);
            } else {
                console.error("ID attribute not found");
            }
        }
  }).disableSelection();
});

// Eisenhover szerinti rendezés mentése
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
            
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Feladatok frissítése
function refreshEisenhoverTasks(){
    clearEisenhoverTasks();
    clearUrgentImportant();
    clearUrgentNotImportant();
    clearNotUrgentImportant();
    clearNotUrgentNotImportant();
    fillEisenhoverTasks();
}

// Feladatok kiürítése
function clearEisenhoverTasks(){
    var eisenhoverBody = document.getElementById("eisenhoverBody");
    var children = eisenhoverBody.children;
    var childrenCount = children.length;
    for (var i = childrenCount - 2; i >= 2; i--) {
        eisenhoverBody.removeChild(children[i]);
    }
}

// Sürgös - Fontos feladatok kiürítése
function clearUrgentImportant(){
    var urgImp = document.getElementById("urgImp");
    var children = urgImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        urgImp.removeChild(children[i]);
    }
}

// Sürgös - Nem fontos feladatok kiürítése
function clearUrgentNotImportant(){
    var urgNotImp = document.getElementById("urgNotImp");
    var children = urgNotImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        urgNotImp.removeChild(children[i]);
    }
}

// Nem sürgös - Fontos feladatok kiürítése
function clearNotUrgentImportant(){
    var notUrgImp = document.getElementById("notUrgImp");
    var children = notUrgImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        notUrgImp.removeChild(children[i]);
    }
}

// Nem sürgös - Nem fontos feladatok kiürítése
function clearNotUrgentNotImportant(){
    var notUrgNotImp = document.getElementById("notUrgNotImp");
    var children = notUrgNotImp.children;

    for (var i = children.length - 2; i >= 2; i--) {
        notUrgNotImp.removeChild(children[i]);
    }
}

// Táblázatok feltöltése
function fillEisenhoverTasks(){
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
              fillTaskTable(response, "eisenhover");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Inicializálás
function init(){
    fillEisenhoverTasks();
}

window.addEventListener('load', init, false);