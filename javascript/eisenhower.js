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
                sortTaskByEisenhower(tdId, tableId);
            } else {
                console.error("ID attribute not found");
            }
        }
  }).disableSelection();
});

// Eisenhower szerinti rendezés mentése
function sortTaskByEisenhower(tdId, tableId){
    const id = tdId.split('-')[1];
    $.ajax({
        type: 'POST',
        url: 'queries/sort_by_eisenhower_query.php', 
        data: {
            'taskId': id,
            'tableId': tableId
        },
        credentials: 'same-origin',
        success: function(response) {
            console.log("asd" + response);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Feladatok frissítése
function refreshEisenhowerTasks(){
    clearEisenhowerTasks();
    clearUrgentImportant();
    clearUrgentNotImportant();
    clearNotUrgentImportant();
    clearNotUrgentNotImportant();
    fillEisenhowerTasks();
}

// Feladatok kiürítése
function clearEisenhowerTasks(){
    var eisenhowerBody = document.getElementById("eisenhowerBody");
    var children = eisenhowerBody.children;
    var childrenCount = children.length;
    for (var i = childrenCount - 2; i >= 2; i--) {
        eisenhowerBody.removeChild(children[i]);
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
function fillEisenhowerTasks(){
    $.ajax({
        type: 'POST',
        url: 'queries/task_query.php', 
        data: {},
        credentials: 'same-origin',
        success: function(response) {
            console.log(response + "asd");
            if(response.length == 0){
              return;
            } 
            else{
              fillTaskTable(response, "eisenhower");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Inicializálás
function init(){
    fillEisenhowerTasks();
}

window.addEventListener('load', init, false);