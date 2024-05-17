 <?php
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 require_once __DIR__ . '/../config.php';
 require_once BASE_PATH . '/classes/user.php';


if (isset($_POST['formData']))
{
  session_start();
  $l = mysqli_connect('localhost', 'root', '', 'student_planner');
    $labelname = mysqli_real_escape_string($l, $_POST['labelName']);
    $labelcolor = $_POST["labelColor"];
    $labelsymbolcheck = $_POST["labelIconEnabled"];
    
    if(!empty($labelname)){

        $user = unserialize($_SESSION['user']);

        $user_id = $user->getId();
        if($labelsymbolcheck == "true"){
          $imageSource = isset($_POST['imageSource']) ? $_POST['imageSource'] : null;
        }
        else{
          $imageSource = NULL;
        }
        
        if (!mysqli_query($l, "INSERT INTO `labels` SET 
        `label_id`=NULL,
        `user_id`='".$user_id."',
        `label_name`='".$labelname."',
        `label_color` = '".$labelcolor."',
        `label_symbol`='".$imageSource."'
        ")) {
          echo("Error description: " . mysqli_error($l));
        }
    }

    mysqli_close($l);
}

?>

<div id="newLabelModal" class="custom-modal container">

<!-- Modal content -->
<div class="custom-modal-content">
    <span class="custom-close" id='modify_labels_xButton'>&times;</span>
    <p class='modal-header-text'>Címke adatai</p>
    <p class='preview-label'>Előnézet:</p>
    <div class='ellipse' id='previewDiv'>
        <p id='previewText' class='preview-text'></p>   
        <img id='previewImage' class='preview-image' alt='Címke előnézet ikon'></img>
    </div>
    <form method='post' id="newLabelForm">

        <label for="labelname">Név:</label>
        <input type="text" id="labelname" name="labelname" maxlength="50"></input><span class="error label-name-error" id="labelNameError">A címke neve nem lehet üres!</span><br><br>

        <label for='labelcolor' >Szín:</label> 
        <input type='color' id='labelcolor' class='colorpicker task-input' name='labelcolor'><br><br>

        <div class="symbol-container">
        <input type="hidden" name="labelsymbolcheck" value="0">
        <input type='checkbox' id='enableLabelIcon' name='labelsymbolcheck'> 
        <label for='enableLabelIcon' >Szimbólum:</label>
        <div class="symbol-square" data-toggle="popover" tabindex="0" role="button" title="Válasszon szimbólumot!" data-content="Content" id='symbolSquare'></div><br><br>
        </div>

<!-- Hidden content for popover -->
<div id="popover-content" class='display-none'>
  <div class="image-container">
    <img src="pictures/rhombus.svg" alt="Rombusz ikon" id='rhombus_icon'/>
    <img src="pictures/waves.svg" alt="Hullám ikon" id='waves_icon'>
    <img src="pictures/star.svg" alt="Csillag ikon" id='star_icon'>
    <img src="pictures/clock.svg" alt="Óra ikon" id='clock_icon'>
    <img src="pictures/ball.svg" alt="Kosárlabda ikon" id='ball_icon'>
    <img src="pictures/pc.svg" alt="Számítógép ikon" id='pc_icon'>
    <img src="pictures/book.svg" alt="Könyv ikon" id='book_icon'>
    <img src="pictures/cart.svg" alt="Bevásárlókocsi ikon" id='cart_icon'>
    <img src="pictures/apple.svg" alt="Alma ikon" id='apple_icon'>
    <!-- Add more images here -->
  </div>
</div>

<br>
    <input type="hidden" id="hiddenId">
    <input type='submit' name='labels_save_button' value='Mentés' class='btn btn-success' id='saveLabelSetting'>
    <button type='button' class='btn btn-primary' id='modify_labels_cancelButton'>Mégsem</button>
    </form>
</div>

</div>