

function createNewGroup(){

    $('#groupModal').modal('show');
}


function addNewMember(event){
    event.preventDefault();
    showAddMembersModal();
}


function setMembersModalHeader(){
    var groupName = document.getElementById('groupName');
    var membersModalHeader = document.getElementById('membersModalHeader');
    if(groupName.value != ""){
    membersModalHeader.textContent = "Tagok hozzáadása a \"" + groupName.value + "\" csoporthoz";
    }
    else{
    membersModalHeader.textContent = "Tagok hozzáadása a \"Névtelen\" csoporthoz";
    }
}


function showAddMembersModal(){
    setMembersModalHeader();

    var modalDiv = document.getElementById("modalDiv");
    var addMembersModal = document.getElementById("addMembersModal");
    
    modalDiv.appendChild(addMembersModal);
    addMembersModal.style.display = "block";
}





function init(){
    document.getElementById('createNewGroup').addEventListener('click', createNewGroup, false);
    document.getElementById('addNewMember').addEventListener('click', addNewMember, false);
    
}

window.addEventListener('load', init, false);