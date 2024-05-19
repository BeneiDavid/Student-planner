// Címkék modal tartalmának lekérdezése
var getModalContent = async function() {
  var labelPopoverContentDiv = document.getElementById("labelPopoverContentDiv");
  labelPopoverContentDiv.innerHTML = "";
  var showGroups = document.getElementById('showGroupLabelsCheckbox').checked;
  await listUserLabels(true, showGroups);
  
  return $('#labelPopoverContentDiv').html();
};

// Oldal betöltés kezelése
$(document).ready(function(){
  
    $(document).on("click", ".sort-by-label-content", function() {
      var divid = $(this).attr('id');
      chooseLabel(divid);
      var sortByLabelModal = document.getElementById("sortByLabelModal");
      sortByLabelModal.style.display = "none";
    });
    
});
  

// Címke kiválasztása
function chooseLabel(divId){
  var chosenLabelId = document.getElementById('chosenLabelId');
  chosenLabelId.value = divId;
  var divIdParts = divId.split('_');
  var labelId = parseInt(divIdParts[divIdParts.length - 1]);

  $.ajax({
    type: 'POST',
    url: 'queries/task_query.php', 
    data: {'labelId': labelId},
    dataType: "json",
    credentials: 'same-origin',
    success: function(response) {
        var labelsHeader = document.getElementById('labelsHeader');
        if(response.length == 0){
          removeTasks();
          labelsHeader.textContent = "A címkéhez nincs feladat rendelve";
        } 
        else{
          var labelName = document.getElementById(divId);
          labelsHeader.textContent = "A(z) \"" + labelName.textContent + "\" címkével megjelölt feladatok";
          fillTaskTable(response, "byLabel");
        }

        var choseLabelDiv = document.getElementById(divId);
        var chooseLabelDiv = document.getElementById("chooseLabel");
        
        chooseLabelDiv.innerHTML = "";
        var clone = choseLabelDiv.cloneNode(true);
        clone.style.marginBottom = "0px";
        chooseLabelDiv.appendChild(clone);
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
    }
});
 
}

// Feladatok eltávolítása
function removeTasks(){
  var labelBody = document.getElementById("labelBody");

  while (labelBody.firstChild && labelBody.childElementCount > 1) {
      labelBody.removeChild(labelBody.firstChild);
  }
}

// Első címke betöltése
async function loadFirstLabel(){
  var labelId = await getFirstLabel();
  
  return new Promise((resolve, reject) => {
  
  if(labelId != ""){
    var chosenLabelId = document.getElementById('chosenLabelId');
    chosenLabelId.value =  "div_" + labelId;
    chooseLabel("div_" + labelId);
    resolve();
  }
  else{
    var labelsHeader = document.getElementById('labelsHeader');
    labelsHeader.textContent = "Még nem hozott létre címkéket. A feladat létrehozás ablakból ezt megteheti.";
    resolve();
  }
});
}

// Első címke lekérdezése
async function getFirstLabel(){
  return new Promise((resolve, reject) => {
    var showGroups = document.getElementById('showGroupLabelsCheckbox').checked;

      $.ajax({
        type: 'POST',
        url: 'queries/first_label_query.php', 
        data: {'showGroups': showGroups},
        credentials: 'same-origin',
        dataType: 'text',
        success: function(response) {
          resolve(response);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            reject(new Error("AJAX request failed"));
        }
    });
  });
}

// Címke létezésének lekérdezése
async function labelExists(id){
  var showGroups = document.getElementById('showGroupLabelsCheckbox').checked;

  return new Promise((resolve, reject) => {
    $.ajax({
      type: 'POST',
      url: 'queries/label_exists_query.php', 
      data: {'labelId': id, 'showGroups': showGroups},
      credentials: 'same-origin',
      dataType: 'text',
      success: function(response) {
        resolve(response);
      },
      error: function(xhr) {
          console.error(xhr.responseText);
          reject(new Error("AJAX request failed"));
      }
  });
});
}

// Feladatok frissítése
async function refreshSortByLabelTasks(){
  var chosenLabelId = document.getElementById('chosenLabelId');
  var labelId = await getFirstLabel();
  
  if(labelId != '' && chosenLabelId.value == ''){
    chosenLabelId.value = "div_" + labelId;
  }

  if(labelId == ''){
    chosenLabelId.value = '';
  }

  else if(await labelExists(chosenLabelId.value.split("_")[1]) == "false"){
    labelId = await getFirstLabel();
    chosenLabelId.value = "div_" + labelId;
  }

  if(labelId == ''){
    var emptyLabel = document.createElement('div');
    emptyLabel.classList.add("ellipse");
    emptyLabel.classList.add("clickable");
    emptyLabel.classList.add("emptylabel");
    emptyLabel.id = "emptyLabel";
    var selectedLabel = document.getElementById('chooseLabel');
    selectedLabel.innerHTML = "";
    selectedLabel.appendChild(emptyLabel);
    var labelsHeader = document.getElementById('labelsHeader');
    labelsHeader.textContent = "Még nem hozott létre címkéket. A feladat létrehozás ablakból ezt megteheti.";
    removeTasks();
  }
  else{
    if(chosenLabelId != ''){
      document.getElementById('')

      var selectedLabel = document.getElementById('chooseLabel');
      if(selectedLabel.firstChild.id == 'emptyLabel'){
        initializeLabels();
      }
      else{
        chooseLabel(chosenLabelId.value);
      } 
    }
  } 
}

// Címke kiválasztás kattintás
function onChooseLabelClick(){
  hideAddLabelModal();
  hideNewLabelModal();
  resetNewLabelModal();
  var sortByLabelModal = document.getElementById("sortByLabelModal");
  var labelBox = document.getElementById("labelBox");
  labelBox.appendChild(newLabelModal);

  getModalContent().then(function(content) {
    var sortByLabelModalLabels = document.getElementById("sortByLabelModalLabels");
    if(content == ''){
      content = "Még nem hozott létre címkéket. A feladat létrehozás ablakból ezt megteheti.";
    }
    sortByLabelModalLabels.innerHTML = content;
    sortByLabelModal.style.display = "block";
  });
  
}

// Címkék modal bezárása
function closeSortByLabelModal(){
  var sortByLabelModal = document.getElementById("sortByLabelModal");
  sortByLabelModal.style.display = "none";
} 

// Címkék inicializálása
async function initializeLabels(){
  var showGroups = document.getElementById('showGroupLabelsCheckbox').checked;
  await listUserLabels(true, showGroups);
  
  loadFirstLabel();
}

// Csoport címkék megjelenítése/elrejtése
function showGroupLabelsCheckboxChanged(){
  refreshSortByLabelTasks();
}

// Inicializálás
function init(){
  document.getElementById('chooseLabel').addEventListener('click', onChooseLabelClick, false);
  document.getElementById('sortByLabel_xButton').addEventListener('click', closeSortByLabelModal, false);
  document.getElementById('showGroupLabelsCheckbox').addEventListener('change', showGroupLabelsCheckboxChanged ,false);
  initializeLabels();
}

// Ablak kattintás kezelése
window.onclick = function(event) {
  var sortByLabelModal = document.getElementById("sortByLabelModal");
  if (event.target == sortByLabelModal) {
    sortByLabelModal.style.display = "none";
  }
}

window.addEventListener('load', init, false);