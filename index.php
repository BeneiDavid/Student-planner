<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 

?>

<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Planner</title>

  <!-- Latest compiled and minified CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Latest compiled JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <!-- JQUERY -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  <!-- JQUERY UI -->
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

  <!-- JQUERY UI Touch Punch for task sorting on touch screen devices -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

  <!-- CSS file -->
  <link rel="stylesheet" href="stylesheets/style.css">
    
</head>
<body>

<?php
require_once 'classes/user.php';
require_once 'classes/loginvalidator.php';

if (isset($_POST['login_button']))
{
  $l = mysqli_connect('localhost', 'root', '', 'student_planner');
  
  $validator = new LoginValidator($l);
  
  if(!$validator->validateForm()){
    $_SESSION['login_email'] = $validator->getAddress();
    $_SESSION['login_error'] = $validator->getError();
  }
  else {
    $_SESSION['login_email'] = $validator->getAddress();
  }

  $user = new User($l, $_SESSION['login_email']); 
  $_SESSION['user'] = serialize($user);
  mysqli_close($l);
}

?>

<nav class="navbar navbar-expand-md navbar-custom navbar-dark">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navigationBar">
    <span class="sr-only">Navigációs sáv kinyitása</span>
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-header">
    </button>
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Student planner</a>
    </div>
  </div>
    <div class="collapse navbar-collapse" id="navigationBar">
      <ul class="nav navbar-nav">
        <li>
          <a class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] == '' || $_GET['page'] == 'kezdolap') ? 'active' : ''; ?>" href="index.php">Kezdőlap</a>
        </li>
        <?php
          $page = isset($_GET['page']) ? $_GET['page'] : '';
          if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != "yes")
          {
            echo '<li><a class="nav-link ';
            echo ($page  == 'bejelentkezes') ? 'active' : '';
            echo '" href="index.php?page=bejelentkezes">Bejelentkezés</a></li>';
            echo '<li><a class="nav-link ';
            echo ($page  == 'regisztracio') ? 'active' : '';
            echo '" href="index.php?page=regisztracio">Regisztráció</a></li>';  
          }
          else{
            if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'student') {
              echo '<li class="nav-item dropdown"> ';
              echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Naptár</a>';
              echo '  <ul class="dropdown-menu">';
              echo '    <li><a class="dropdown-item" href="index.php?page=napi_megjelenites">Napi megjelenítés</a></li>';
              echo '    <li><a class="dropdown-item" href="index.php?page=heti_megjelenites">Heti megjelenítés</a></li>';
              echo '    <li><a class="dropdown-item" href="index.php?page=havi_megjelenites">Havi megjelenítés</a></li>';
              echo '  </ul>';
              echo '</li>';

              echo '<li class="nav-item dropdown"> ';
              echo '<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Rendszerezés segítő</a>';
              echo '  <ul class="dropdown-menu">';
              echo '    <li><a class="dropdown-item" href="index.php?page=folyamat">Folyamat alapú rendezés</a></li>';
              echo '    <li><a class="dropdown-item" href="index.php?page=eisenhower">Eisenhower mátrix</a></li>';
              echo '    <li><a class="dropdown-item" href="index.php?page=cimkeszerint">Feladatok címkék szerint</a></li>';
              echo '  </ul>';
              echo '</li>';

              echo '<li><a class="nav-link ';
              echo ($page  == 'csoportok') ? 'active' : '';
              echo '" href="index.php?page=csoportok">Csoportok</a></li>';

              echo '<li><a class="nav-link ';
              echo ($page  == 'uzenetek') ? 'active' : '';
              echo '" href="index.php?page=uzenetek">Üzenetek</a></li>';

              
            }
            else{
              echo '<li><a class="nav-link ';
              echo ($page  == 'csoportok_tanar') ? 'active' : '';
              echo '" href="index.php?page=csoportok_tanar">Csoportok</a></li>';

              echo '<li><a class="nav-link ';
              echo ($page  == 'uzenetek_tanar') ? 'active' : '';
              echo '" href="index.php?page=uzenetek_tanar">Üzenetek</a></li>';
            }
              echo '<li><a class="nav-link ';
              
              echo ($page  == 'jelszo_valtoztatas') ? 'active' : '';
              echo '" href="index.php?page=jelszo_valtoztatas">Jelszó változtatás</a></li>';

              echo '<li><a class="nav-link ';
              echo ($page  == 'kijelentkezes') ? 'active' : '';
              echo '" href="index.php?page=kijelentkezes">Kijelentkezés</a></li>';
          }

        ?>
      </ul>
    </div>
</nav>
<div class="container-fluid">

    <?php
    $l = mysqli_connect('localhost', 'root', '', 'student_planner');

    if(isset($_GET['page'])){
    switch($_GET['page'])
    {
      case 'regisztracio': include 'pages/regisztracio.php';  break;
      case 'bejelentkezes': include 'pages/bejelentkezes.php'; break;
      case 'elfelejtett_jelszo': include 'pages/elfelejtett_jelszo.php'; break;
      case 'confirmation': include 'pages/confirmation.php'; break;
      case 'napi_megjelenites': include 'pages/napi_megjelenites.php'; break;
      case 'heti_megjelenites': include 'pages/heti_megjelenites.php'; break;
      case 'havi_megjelenites': include 'pages/havi_megjelenites.php'; break;
      case 'folyamat': include 'pages/folyamat.php'; break;
      case 'eisenhower': include 'pages/eisenhower.php'; break;
      case 'cimkeszerint': include 'pages/cimkeszerint.php'; break;
      case 'csoportok': include 'pages/csoportok.php'; break;
      case 'uzenetek': include 'pages/uzenetek.php'; break;
      case 'csoportok_tanar': include 'pages/csoportok_tanar.php'; break;
      case 'uzenetek_tanar': include 'pages/uzenetek_tanar.php'; break;
      case 'jelszo_valtoztatas': include 'pages/jelszo_valtoztatas.php'; break;
      case 'kijelentkezes': include 'pages/kijelentkezes.php'; break;
      

      default: include 'pages/kezdolap.php'; break;
    }
    }
    else{
      include 'pages/kezdolap.php';
    }

    mysqli_close($l);
    ?>

</div>

<script>
  // Tooltipek és popoverek inicializálása
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })
</script>

</body>
</html>