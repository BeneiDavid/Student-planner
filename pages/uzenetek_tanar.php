<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
  $user = unserialize($_SESSION['user']);
  $user_type = $user->getUserType();
  if($user_type != 'teacher'){
      header("Location: index.php?page=kezdolap");
  exit;
  }

  require_once 'config.php';
  require_once BASE_PATH . '/modals/chat_modal.php';

  echo '<script type="text/javascript"  src="javascript/messages_teacher.js"></script>';
  echo '<script type="text/javascript"  src="javascript/chat.js"></script>';
  echo '<script type="text/javascript"  src="javascript/common.js"></script>';
  
  echo "<h1>Üzenetek</h1>";

  echo"  <div class='dropdown container'>
          <div class='align-button-center'>
            <br>
            <button id='chooseGroupButton' class='btn btn-primary dropdown-toggle align-button-center  display-messages-button' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
              Válasszon csoportot
            </button>
            <div class='no-created-groups-div display-messages-div' id='noCreatedGroupsDiv'>Ön még nem hozott létre csoportokat.</div>
            <ul class='dropdown-menu' id='groupDropdownList'>
            </ul>
          </div>

          <br><br>

          <h2 id='selectedGroupNameHeader' class='selected-group-name-header'></h2>

          <div id='groupMembersDiv' class='group-members-div-teacher'></div>";
}
else{
  echo '<br><div class="content-padding"><div class="alert alert-info succesful-login-alert" role="alert"><a class="link-custom-color" href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></div></div>';
}

?>

<script>
  document.title = "Student Planner - Üzenetek";
</script>