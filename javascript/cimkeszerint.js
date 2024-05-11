var getModalContent = async function() {
  var labelPopoverContentDiv = document.getElementById("labelPopoverContentDiv");
  labelPopoverContentDiv.innerHTML = "";
  await listUserLabels(true);
  
  return $('#labelPopoverContentDiv').html();
};


  
$(document).ready(function(){
  
    $(document).on("click", ".sort-by-label-content", function() {
      var divid = $(this).attr('id');
      chooseLabel(divid);
      var sortByLabelModal = document.getElementById("sortByLabelModal");
      sortByLabelModal.style.display = "none";
    });
    
});
  
  document.addEventListener('DOMContentLoaded', function() {
});


function chooseLabel(divId){
  var chosenLabelId = document.getElementById('chosenLabelId');
  chosenLabelId.value = divId;
  var divIdParts = divId.split('_');
  var labelId = parseInt(divIdParts[divIdParts.length - 1]);

  $.ajax({
    type: 'POST',
    url: 'task_query.php', 
    data: {'labelId': labelId},
    dataType: "json",
    credentials: 'same-origin',
    success: function(response) {
      console.log("itten");
        console.log(response);
        var labelsHeader = document.getElementById('labelsHeader');
        if(response.length == 0){
          removeTasks();
          labelsHeader.textContent = "A címkéhez nincs feladat rendelve";
        } 
        else{
          console.log(divId);
          var labelName = document.getElementById(divId).textContent;
          labelsHeader.textContent = "A(z) \"" + labelName + "\" címkével megjelölt feladatok";
          fillTaskTable(response, "byLabel");
        }

        var choseLabelDiv = document.getElementById(divId);
        var chooseLabelDiv = document.getElementById("chooseLabel");
        
        chooseLabelDiv.innerHTML = "";
        chooseLabelDiv.appendChild(choseLabelDiv.cloneNode(true));
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
    }
});
 
}



  function removeTasks(){
    var labelBody = document.getElementById("labelBody");

    while (labelBody.firstChild && labelBody.childElementCount > 1) {
        labelBody.removeChild(labelBody.firstChild);
    }
  }

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


  async function getFirstLabel(){
    return new Promise((resolve, reject) => {
        $.ajax({
          type: 'POST',
          url: 'first_label_query.php', 
          data: {},
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

  async function labelExists(id){
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'POST',
        url: 'label_exists_query.php', 
        data: {'labelId': id},
        credentials: 'same-origin',
        dataType: 'text',
        success: function(response) {
          console.log(response);
          resolve(response);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            reject(new Error("AJAX request failed"));
        }
    });
  });
  }

  async function refreshSortByLabelTasks(){
    var chosenLabelId = document.getElementById('chosenLabelId');
    // Check if there are still labels
    var labelId = await getFirstLabel();
    if(labelId != '' && chosenLabelId.value == ''){
      chosenLabelId.value = "div_" + labelId;
    }
    else if(labelId == ''){
      chosenLabelId.value = '';
    }

    if(await labelExists(chosenLabelId.value.split("_")[1]) == "false"){
      labelId = await getFirstLabel();
      chosenLabelId.value = "div_" + labelId;
    }

    if(chosenLabelId.value == ''){
      var emptyLabel = document.createElement('div');
      emptyLabel.classList.add("ellipse");
      emptyLabel.classList.add("clickable");
      emptyLabel.classList.add("emptylabel");
      var selectedLabel = document.getElementById('chooseLabel');
      selectedLabel.innerHTML = "";
      selectedLabel.appendChild(emptyLabel);
      var labelsHeader = document.getElementById('labelsHeader');
      labelsHeader.textContent = "Még nem hozott létre címkéket. A feladat létrehozás ablakból ezt megteheti.";
      removeTasks();
    }
    else{
      if(chosenLabelId != ''){
        chooseLabel(chosenLabelId.value);
      }
    } 

  }

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

  function closeSortByLabelModal(){
    var sortByLabelModal = document.getElementById("sortByLabelModal");
    sortByLabelModal.style.display = "none";
  } 

async function initializeLabels(){
  await listUserLabels(true);
  
  loadFirstLabel();
}


  function init(){
    document.getElementById('chooseLabel').addEventListener('click', onChooseLabelClick, false);
    document.getElementById('sortByLabel_xButton').addEventListener('click', closeSortByLabelModal, false);
    initializeLabels();

  }


  window.onclick = function(event) {
    var sortByLabelModal = document.getElementById("sortByLabelModal");
    if (event.target == sortByLabelModal) {
      sortByLabelModal.style.display = "none";
    }
  }

  window.addEventListener('load', init, false);