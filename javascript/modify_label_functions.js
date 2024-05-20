// Címke módosítás oldal mentése
function saveLabelSetting(event){
    var previewImage = document.getElementById("previewImage");
    var imageSource = previewImage.src;
    var labelname = document.getElementById("labelname");
    var labelName = labelname.value;
    var labelcolor = document.getElementById("labelcolor");
    var labelColor = labelcolor.value;
    var labeliconenabled = document.getElementById("enableLabelIcon");
    var labelIconEnabled = labeliconenabled.checked;
    
    event.preventDefault(); 
  
    if(labelName.length === 0){
      showLabelNameError();
      return;
    }

    var hidden_id = document.getElementById('hiddenId');
    var label_id = hidden_id.value;

    if (label_id !== undefined && label_id !== null && label_id !== "") { 
      $.ajax({
          type: 'POST',
          url: 'queries/edit_label_query.php', 
          data: {'label_id': label_id,
                'label_symbol': imageSource,
                'label_name': labelName,
                'label_color': labelColor,
                'label_enabled': labelIconEnabled
          },
          credentials: 'same-origin',
          success: function(response) {
              clearLabelModalLabels();
              listUserLabels(false, false);
              hideLabelNameError();
              hideNewLabelModal();
              resetNewLabelModal();
              showAddLabelModal();
              clearHiddenId();

          },
          error: function(xhr) {
              console.error(xhr.responseText);
          }
      });
    }
    else{
      var formData = $(this).serialize();
  
      $.ajax({
          type: 'POST',
          url: 'modals/new_label_modal.php', 
          data: {'formData': formData,
                'imageSource': imageSource,
                'labelName': labelName,
                'labelColor': labelColor,
                'labelIconEnabled': labelIconEnabled
          },
          credentials: 'same-origin',
          success: function(response) {
            console.log(response);
              clearLabelModalLabels();
              listUserLabels(false, false);
              hideLabelNameError();
              hideNewLabelModal();
              resetNewLabelModal();
              showAddLabelModal();
          },
          error: function(xhr) {
              console.error(xhr.responseText);
          }
      });
    }
}

// Címke módosítás modal bezárása
function closeModifyLabels(){
    hideNewLabelModal();
    showAddLabelModal();
    hideLabelNameError();
    resetNewLabelModal();
    clearHiddenId();
} 

// Rejtett input mező értékének törlése
function clearHiddenId(){
    var hidden_id = document.getElementById('hiddenId');
    hidden_id.value = "";
}

// Címke módosítás modal beállíása
function createNewLabelModal(event, label_id){
    
    if (typeof label_id !== 'undefined' && label_id != "") {
      var hiddenId = document.getElementById("hiddenId");
      hiddenId.value = label_id;
      setLabelDataToEdit(label_id);
    }
    else{
      setSymbolSquareAndPreview();
    }
  
    hideAddLabelModal();
    var modalDiv = document.getElementById("modal_div");
    var newLabelModal = document.getElementById("newLabelModal");
    modalDiv.appendChild(newLabelModal);
  
    changeLabelColor();
    showNewLabelModal();
}

// Új címke alaphelyzetbe állítása
function resetNewLabelModal(){

    var labelname = document.getElementById("labelname");
    var labelcolor = document.getElementById("labelcolor");
    var labeliconenabled = document.getElementById("enableLabelIcon");

    labelname.value = "";
    labelcolor.value = "#000000";
    labeliconenabled.checked = false;

    changeLabelColor();
    changeLabelName();
    enableLabelIconChanged(); 

    symbolSquare = document.getElementById("symbolSquare");
    symbolSquare.innerHTML = "";
    var img = document.createElement("img");
    img.src = "pictures/rhombus.svg";
    img.width = 25;
    img.height = 25;
    symbolSquare.appendChild(img);
}

// Címke módosítás modal elrejtése
function hideNewLabelModal(){
    var newLabelModal = document.getElementById("newLabelModal");
    newLabelModal.style.display = "none";
}
  
// Címke módosítás modal megjelenítése
function showNewLabelModal(){
var newLabelModal = document.getElementById("newLabelModal");
newLabelModal.style.display = "block";
}

// Címke név hiba megjelenítése
function showLabelNameError(){
    labelNameError = document.getElementById("labelNameError");
    labelNameError.style.display = "inline-block";
}
  
// Címke név hiba elrejtése
function hideLabelNameError(){
labelNameError = document.getElementById("labelNameError");
labelNameError.style.display = "none";
}

// Címke megjelenítése / elrejtése a previewban
function enableLabelIconChanged(){
    previewImage = document.getElementById("previewImage");

    if(!this.checked){
      previewImage.style.display = 'none';
    }
    else{
      previewImage.style.display = 'inline-block';
    }
}

// Szimbólum négyzet, preview beállítása 
function setSymbolSquareAndPreview(){
    var symbolSquare = document.getElementById("symbolSquare");
    
    if(!symbolSquare.hasChildNodes()){
      var img = document.createElement("img");
      img.src = "pictures/rhombus.svg";
      img.width = 25;
      img.height = 25;
      symbolSquare.appendChild(img);
      previewImage = document.getElementById("previewImage");
      previewImage.src = "pictures/rhombus.svg";
    }

    var previewDiv = document.getElementById("previewDiv");
    var labelcolor = document.getElementById("labelcolor");
    previewDiv.style.backgroundColor = labelcolor.value;
}
    
// Preview címke, kép, háttér szín változatása
function changeLabelColor(){
    var updatedColor = this.value;
    var previewDiv = document.getElementById("previewDiv");
    var previewText = document.getElementById("previewText");
    previewDiv.style.backgroundColor = updatedColor;
    var style = window.getComputedStyle(previewDiv);
    var color = style.backgroundColor;
    var newTextColor = getContrastColor(color);
  
    if(newTextColor == 'black'){
      previewImage.style.filter = "";
    }
    else{
      previewImage.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
    }
  
    previewText.style.color = newTextColor;
}

// Címke módosítás esetén a címke adatainak betöltése
function setLabelDataToEdit(label_id){
  div_id = "div_" + label_id;
  var label_div = document.getElementById(div_id);

  var symbolSquare = document.getElementById("symbolSquare");
  
  if (symbolSquare.firstChild) {
    symbolSquare.removeChild(symbolSquare.firstChild);
  }
    var img = document.createElement("img");
    
  var imageElement = document.querySelector('#'+ div_id + ' img');
  if(imageElement.style.display == 'inline-block'){
    previewImage.style.display = 'inline-block';
    var enableLabelIcon = document.getElementById("enableLabelIcon");
    enableLabelIcon.checked = true;
  }

  if (imageElement.src && imageElement.src.endsWith(".svg")) {
    img.src = imageElement.src;
  } else {
    img.src = "pictures/rhombus.svg";
  }
  
  img.width = 25;
  img.height = 25;
  symbolSquare.appendChild(img);
  previewImage = document.getElementById("previewImage");
  previewImage.src = img.src;
  
  var previewDiv = document.getElementById("previewDiv");
  previewDiv.style.backgroundColor = label_div.style.backgroundColor;
  labelcolor.value = rgbToHex(label_div.style.backgroundColor);

  var pElement = label_div.querySelector('p'); 
  var textContent = pElement.textContent;
  var labelname = document.getElementById("labelname");
  var previewText = document.getElementById("previewText");
  labelname.value = textContent;
  previewText.textContent = textContent;
  
}

// Címke név frissítése
function changeLabelName(){
    var previewImage = document.getElementById("previewImage");
    var inputValue = this.value;
    var previewText = document.getElementById("previewText");
    var previewDiv = document.getElementById("previewDiv");
    var style = window.getComputedStyle(previewDiv);
    var color = style.backgroundColor;
    var newTextColor = getContrastColor(color);
  
    if(newTextColor == 'black'){
      previewImage.style.filter = "";
    }
    else{
      previewImage.style.filter = "invert(100%) sepia(100%) saturate(0%) hue-rotate(248deg) brightness(106%) contrast(106%)";
    }
  
    previewText.textContent = inputValue;
    previewText.style.color = newTextColor;
  
}
  
// Inicializálás
function init(){
    document.getElementById('createNewLabel').addEventListener('click', createNewLabelModal, false);
    document.getElementById('saveLabelSetting').addEventListener('click', saveLabelSetting, false);
    document.getElementById('modify_labels_cancelButton').addEventListener('click', closeModifyLabels,false);
    document.getElementById('modify_labels_xButton').addEventListener('click', closeModifyLabels,false);
    document.getElementById('labelcolor').addEventListener('change', changeLabelColor, false);
    document.getElementById('labelname').addEventListener('input', changeLabelName, false);
    document.getElementById('enableLabelIcon').addEventListener('change', enableLabelIconChanged, false);   
}

window.addEventListener('load', init, false);


/*----------- Ikonválasztó popover funkciói START-----------*/
  
$(document).ready(function(){
    
    $('[data-toggle="popover"]').popover({
      html: true,
      content: function() {
        return $('#popover-content').html();
      }
    });
  
    $(document).on("click", ".image-container img", function() {
      var src = $(this).attr('src');
      symbolSquare = document.getElementById("symbolSquare");
      symbolSquare.innerHTML = "";
      var img = document.createElement("img");
      img.src = src;
      img.width = 25;
      img.height = 25;
      symbolSquare.appendChild(img);
      previewImage = document.getElementById("previewImage");
      previewImage.src = src;
      $('[data-toggle="popover"]').popover('hide');
    });
    
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.popover').length && !$(e.target).closest('[data-toggle="popover"]').length) {
        $('[data-toggle="popover"]').popover('hide');
      }
    });
});
  
  /*----------- Ikonválasztó popover funkciói END-----------*/