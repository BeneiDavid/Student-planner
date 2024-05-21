// Címke hozzáadás modal bezárása
function closeAddLabels(){
    hideAddLabelModal();
} 
  
// Címke módosítása
function editLabel(event){
    var parentDiv = event.target.parentNode;
    var clickedId = parentDiv.id;
    var parts = clickedId.split('_');
    var number = parseInt(parts[parts.length - 1]);
    createNewLabelModal(event, number);
}
  
// Címke törlése
function deleteLabel(event){
    var clickedId = event.target.id;
    
    var parts = clickedId.split('_');
    var number = parseInt(parts[parts.length - 1]);
  
    $.ajax({
      type: 'POST',
      url: 'queries/delete_label_query.php', 
      data: {'label_id': number},
      credentials: 'same-origin',
      success: function() {
          refreshTaskLabels();
      },
      error: function(xhr) {
          console.error(xhr.responseText);
      }
  });
  
  //need to delete the div from the page so it won't stutter
  var divToRemove = document.getElementById("div_" + number);
  var checkboxToRemove = document.getElementById("checkbox_" + number);
  var brToRemove = document.getElementById("br_" + number);
  
  divToRemove.parentNode.removeChild(divToRemove);
  checkboxToRemove.parentNode.removeChild(checkboxToRemove);
  brToRemove.parentNode.removeChild(brToRemove);
  event.target.parentNode.removeChild(event.target);
}

// Címkék frissítése
function refreshTaskLabels(){
    var form = document.getElementById("addLabelForm");
    var checkboxes = form.querySelectorAll('input[type="checkbox"]');
    var checkedIds = [];
    var checkedDivs = [];
    
    // Lekérdezzük a bejelölt checkboxokat
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            var checkboxId = checkbox.id;
            checkedIds.push(checkboxId);
        }
    });
    
    // A checkboxokhoz tartozó címkék lekérdezése
    checkedIds.forEach(function(checkboxId) {
        var divId = checkboxId.replace('checkbox_', 'div_');
        var div = document.getElementById(divId);
        if (div) {
            checkedDivs.push(div);
        }
    });
    
  
    var added_labels = document.getElementById("added_labels");
    
    added_labels.innerHTML = "";
    
    // A címkéket másolat készítés után megjelenítjük és beállítjuk
    checkedDivs.forEach(function(div) {
      var divCopy = div.cloneNode(true);
      divCopy.classList.remove('clickable');
      divCopy.classList.add('no-select');
      divCopy.id = divCopy.id.replace('div_', 'copy_');
      added_labels.appendChild(divCopy);
  });
  
    var img = setAndGetAddLabelImage();
    fillAddedLabels(img);
    added_labels.appendChild(img);
}

// Címke hozzáadás mentése esetén task oldal feltöltése
function saveAddLabel(event){
    event.preventDefault();
    refreshTaskLabels();
    hideAddLabelModal();
}
  
// Címke hozzáadás modal elrejtése
function hideAddLabelModal(){
    var addLabelModal = document.getElementById("addLabelModal");
    addLabelModal.style.display = "none";
}
  
// Címke hozzáadás modal megjelenítése
function showAddLabelModal(){
    var addLabelModal = document.getElementById("addLabelModal");
    addLabelModal.style.display = "block";
}

// Címke hozzádás modal jelenjen meg kattintásra
function addLabelModalClick(){
    var modalDiv = document.getElementById("modal_div");
    var addLabelModal = document.getElementById("addLabelModal");
    clearLabelModalLabels();
    listUserLabels(false, false);
    
    modalDiv.appendChild(addLabelModal);
    addLabelModal.style.display = "block";
}

// Felhasználó címkéinek listázása
function listUserLabels(fromLabelPopover, showGroups){

    return new Promise((resolve, reject) => {  
        $.ajax({
        type: 'POST',
        data: {'showGroups': showGroups}, 
        url: 'queries/user_label_query.php',
        dataType: "json",
        credentials: 'same-origin',
        success: function(response) {
            console.log(response + "asd");
            response.forEach(function(item) {
                var newDiv = document.createElement('div');
                var newP = document.createElement('p');
                var newImg = document.createElement('img');

                if(!fromLabelPopover){
                    var newBr = document.createElement('br');
                    var checkbox = document.createElement('input');
                    newBr.id = 'br_' + item.label_id;
                    checkbox.type = 'checkbox';
                    checkbox.style.verticalAlign = "middle";
                    checkbox.id = 'checkbox_' + item.label_id;
                
                    var deleteImg = document.createElement('img');
                    deleteImg.src = "pictures/delete.svg";
                    deleteImg.alt="Címke törlése";
                    deleteImg.id = 'delete_' + item.label_id;
                    deleteImg.style.width = "20px";
                    deleteImg.style.height = "20px";
                    deleteImg.style.marginLeft = "10px";
                    deleteImg.addEventListener('click', deleteLabel, false);
                    deleteImg.classList.add('clickable');
                }

                newP.textContent = item.label_name;
                newImg.src = item.label_symbol;
                newDiv.classList.add('ellipse');
                newP.classList.add('preview-text');
                newImg.classList.add('preview-image');
                newImg.title = "Címke ikon";
                newDiv.classList.add('clickable');
                
                var rgb = hexToRgb(item.label_color);
                var rgbString = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
                var textColor = getContrastColor(rgbString);
                newP.style.color = textColor; 

                if(textColor == 'white'){
                newImg.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
                }

                newDiv.style.backgroundColor = item.label_color;
                newDiv.id = 'div_' + item.label_id;
                

                if(item.label_symbol != ""){
                newImg.style.display = "inline-block";
                }
                
                newDiv.appendChild(newP);
                newDiv.appendChild(newImg);

                if(!fromLabelPopover){
                    newDiv.addEventListener('click', editLabel, false);

                    var addedLabelsDiv = document.getElementById('added_labels');

                    var childDivs = addedLabelsDiv.querySelectorAll('div');
                    
                    childDivs.forEach(function(div) {
                    var parts = div.id.split('_');
        
                    var number = parseInt(parts[parts.length - 1]);
                    if(number == item.label_id){
                        checkbox.checked = "true";
                    }
                    });
        
                    var labelDiv = document.getElementById("addLabelModalLabels");
                    labelDiv.appendChild(checkbox);
                    labelDiv.appendChild(newDiv);
                    labelDiv.appendChild(deleteImg);
                    labelDiv.appendChild(newBr);
                }
                else{
                    newDiv.classList.add("sort-by-label-content");
                    newDiv.style.marginBottom = "10px";
                    var labelPopoverContentDiv = document.getElementById("labelPopoverContentDiv");
                    labelPopoverContentDiv.appendChild(newDiv);

                }

            });
            
            if(!fromLabelPopover){
                refreshTaskLabels();
            }
            else{
                resolve();
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            reject();
        }
        });
    });
}

// Címke hozzáadás modal címkéinek törlése
function clearLabelModalLabels(){
    var addLabelModalLabels = document.getElementById("addLabelModalLabels");
    addLabelModalLabels.innerHTML = "";
}

// Inicializálás
function init(){
    document.getElementById('labels_cancelButton').addEventListener('click', closeAddLabels,false);
    document.getElementById('labels_xButton').addEventListener('click', closeAddLabels,false);
    document.getElementById('saveAddLabel').addEventListener('click', saveAddLabel, false);
}

window.addEventListener('load', init, false);