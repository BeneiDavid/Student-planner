<?php

require_once 'config.php';
require_once BASE_PATH . '/classes/user.php';

if (isset($_GET['login_success']) && $_GET['login_success'] == 1) {
    echo '<br><div class="content-padding"><div class="alert alert-success succesful-login-alert" role="alert">Sikeres bejelentkezés!</div></div>';

  }
  
echo "<h1>Kezdőlap</h1> <br><br>";

echo "<h2 class='content-padding'>A weboldalról</h2>

<div class='content-padding'>
  <br>
  <p>
    Az egyre gyorsuló világban a diákokra egyre több feladat és kötelesség hárul, melyeknek könnyű lehet elveszni a sokaságában. 
    Ezért jött létre ez a weboldal, hogy megkönnyítse a hallgatók napi tevékenységeit.
    A weboldal által kínált funkciók segítséget nyújtanak a hallgatóknak feladataik rendszerezésében és teendőik könnyebben átláthatóságában.
    Ezen kívül, a hallgatókat oktatóik csoportokba szervezhetik, így az iskolai tevékenységeket, feladatokat is nyomon követhetik.
    A weboldalon bejelentkezés után chat funkció is található a hallgatók és oktatók közötti kommunikáció megkönnyítése érdekében.
  </p>
</div>
<br><br>
";

echo "<h2 class='content-padding'>Segítség az oldal használatához</h2>
<div class='content-padding'>
  <br><br>
  <h3>Segítség hallgatóknak</h3> <br>

  <div class='row'>

      <h4>Fiók létrehozása</h4>
      <p>
        A weboldal használatához először hozzon létre egy fiókot a <a href='index.php?page=regisztracio'>Regisztráció</a> oldalon. 
        Itt meg kell adnia hallgatói azonosítóját ami alapján az oktatói meg
        fogják tudni Önt találni és csoportokhoz rendelni. Sikeres regisztráció után kapni fog egy e-mailt egy megerősítő hivatkozással, miután erre
        rákattint lehetősége nyílik a bejelentkezésre. Lehetséges, hogy a megerősítő e-mail a spam mappába érkezik, ezért kérem, hogy ellenőrizze azt. Amennyiben a diákazonosító melyet megadott már foglalt, vagy a regisztráció során bármilyen egyéb problémába ütközik
        vegye fel a kapcsolatot a rendszergazdával a következő e-mail címen: <a href='mailto:beneidavid.it@gmail.com'>beneidavid.it@gmail.com</a>
      </p>


  </div>
  <br>
  <div class='row'>

      <h4>Napi megjelenítés</h4>
      <p>
        A <a href='index.php?page=napi_megjelenites'>Napi megjelenítés</a> segítségével feladatait megtekintheti napi bontásban. 
        A naptár segítségével válthat a megjeleníteni kívánt napok között. 
        Feladatokat a táblázat utolsó sorában lévő plusz gombra kattintva hozhat létre. Egy feladatra kattintva módosíthatja azt.
      </p>
    </div>

  <br>

  <div class='row'>

      <h4>Heti megjelenítés</h4>
      <p>
        A <a href='index.php?page=heti_megjelenites'>Heti megjelenítés</a> segítségével feladatait megtekintheti heti bontásban. 
        A naptár segítségével válthat a megjeleníteni kívánt hetek között. 
        Feladatokat a napok alatti plusz gombokra kattintva hozhat létre, 
        ekkor a feladat létrehozás gyorsítása érdekében az adott nap dátuma automatikusan kitöltésre kerül a feladat létrehozás ablakában.
        Egy feladatra kattintva módosíthatja azt.
        Amennyiben meg szeretné tekinteni a hét egy napjára eső feladatait, kattintson a naptárban a nap nevére. 
      </p>

  </div>

  <br>

  <div class='row'>

      <h4>Havi megjelenítés</h4>
      <p>
        A <a href='index.php?page=havi_megjelenites'>Havi megjelenítés</a> segítségével feladatait megtekintheti havi bontásban. 
        A naptár segítségével válthat a megjeleníteni kívánt hónapok között. 
        Feladatokat a naptár alatti gomb segítségével hozhat létre.
        Egy feladatra kattintva módosíthatja azt.
      </p>

  </div>

  <br>

  <div class='row'>

    <h4>Folyamat alapú rendezés</h4>
    <p>
      A <a href='index.php?page=folyamat'>Folyamat alapú rendezés</a> segítséget nyújt az egyes feladatok állapotának követésében. 
      A létrehozott feladatait könnyedén áthúzhatja valamelyik táblázatba,
      illetve ha nem szeretné őket rendezni visszahúzhatja őket a feladatok közé. Új feladatot a \"Feladatok\" táblázat alatti plusz gomb segítségével hozhat létre.
      Egy feladatra kattintva módosíthatja azt.
    </p>

  </div>
  
  <br>

  <div class='row'>

    <h4>Eisenhover mátrix</h4>
    <p>
      Az <a href='index.php?page=eisenhover'>Eisenhover mátrix</a>-szal való rendezés egy jól bevált módja a fontossági sorrend áttekintésének. 
      Feladatait négy szempont szerint rendezheti egy feladat sürgősségének
       és fontosságának figyelembe vételével.
       A létrehozott feladatait könnyedén áthúzhatja valamelyik táblázatba,
      illetve ha nem szeretné őket rendezni visszahúzhatja őket a feladatok közé. Új feladatot a \"Feladatok\" táblázat alatti plusz gomb segítségével hozhat létre.
      Egy feladatra kattintva módosíthatja azt.
    </p>

  </div>

  <br>

  <div class='row'>

    <h4>Címke szerinti rendezés</h4>
    <p>
      A <a href='index.php?page=cimkeszerint'>Címke szerinti rendezés</a> segítségével kiválaszthat egy címkét, amelyet létrehozott 
      és megtekintheti az ezekhez kapcsolódó feladatait.
      Válasszon ki egy címkét a felugró ablakból amely feladatait meg szeretné nézni. Amennyiben a csoportfeladatai címkéit is látni szeretné 
      jelölje be a táblázat feletti jelölő négyzetet. Új feladatot a táblázat alatti plusz gomb segítségével hozhat létre.
      Egy feladatra kattintva módosíthatja azt.
    </p>

  </div>

  <br>

  <div class='row'>
    
    <h4>Csoportok</h4>
    <p>
      A <a href='index.php?page=csoportok'>Csoportok</a> oldal segítségével áttekintheti az egyes csoportjainak feladatait, illetve üzenetet írhat a csoport oktatójának. 
      A csoportok melleti feladat gomb segítségével egyszerűen válthat a csoportok és feladataik között. 
      Amennyiben ki szeretne lépni egy csoportból a csoport melletti kilépés gomb segítségével megteheti.
    </p>

  </div>

  <br>

  <div class='row'>

    <h4>Üzenetek</h4>
    <p>
      Az <a href='index.php?page=uzenetek'>Üzenetek</a> oldalon megtekintheti oktatóit és csoportjait, illetve üzenetet küldhet oktatójának. 
      Hogy könnyen értesüljön az új üzeneteiről, a \"Chat megnyitása\" gomb mellett egy kék pont jelenik meg.
    </p>

  </div>

  <br><br>

  <h3>Segítség oktatóknak</h3> <br>

  <div class='row'>

    <h4>Fiók létrehozása</h4>
    <p>
      A hallgatók személyes adatainak védelme érdekében oktatói jogosultságot csak a rendszergazda adhat. Amennyiben szeretne felhasználói fiókot
      igényelni kérem keresse meg a rendszergazdát a következő e-mail címen: <a href='mailto:beneidavid.it@gmail.com'>beneidavid.it@gmail.com</a>
    </p>

  </div>

  <br>

  <div class='row'>

    <h4>Csoportok</h4>
    <p>
      A <a href='index.php?page=csoportok_tanar'>Csoportok</a> oldal segítségével létrehozhat csoportokat, feladatokat rendelhet hozzájuk és üzenetet 
      írhat egy csoport hallgatóinak részére.
      A létrehozott csoportok melletti Feladatok gombra kattintva megtekintheti egy csoport feladatait és a táblázat utolsó sorában lévő
      plusz gombra kattintva új feladatot hozhat létre. Egy meglévő feladatra kattintva módosíthatja azt.
      <br>
      Ha szeretne hallgatókat rendelni egy csoporthoz, a csoport létrehozás ablakából válassza az \"Új tag hozzáadása\" opciót.
      A tagok hozzáadása ablakból könnyedén beazonosíthatja a hallgatókat név és azonosító alapján, továbbá segítséget nyújt a kereső mező is, 
      mellyel bármelyikre rákereshet. Jelölje be a hallgatók melletti jelölőnégyzetet akiket hozzá szeretne adni a csoporthoz, majd kattintson a \"Tagok hozzáadása\"
      gombra. Amennyiben egy hallgatót mégsem szeretne a csoporthoz adni, a Csoport adatai ablakban kattintson a hallgató melletti mínusz gombra. 
      <br>
      Az oldal lehetőséget nyújt ezek mellett csoportüzenet küldésére is. A \"Csoportüzenet\" ablakban a \"Tagok hozzáadása\" ablakhoz hasonlóan 
      kereshet a csoport hallgatói között,
      és bejelölheti a hallgatókat akiknek üzenetet szeretne küldeni. Amennyiben az egész csoport részére szeretne üzenetet küldeni, jelölje be az \"Üzenet küldése az 
      egész csoportnak\" opciót.
    </p>
  </div>

  <br>

  <div class='row'>
    <h4>Üzenetek</h4>
    <p>
      Az <a href='index.php?page=uzenetek_tanar'>Üzenetek</a> oldalon megtekinthei üzenetváltásait csoportokra bontva. Válassza ki a csoportot a legördülő ablakból 
      és kattintson a \"Chat megnyitása\" gombra 
      valamelyik hallgató mellett a csevegés elkezdéséhez. Hogy könnyen értesüljön az új üzeneteiről, a \"Chat megnyitása\" gomb mellett egy kék pont jelenik meg.
    </p>
  </div>

  <br><br>

  <h3>Általános segítség</h3> <br>

  <div class='row'>
    <h4>Feladatok beállítása</h4>
    <p>
      A \"Feladat adatai\" ablakban lehetősége nyílik beállítani egy feladat nevét, a listázáskor megjelenítendő színét, határidejét, címkéit és leírását.
      Címkék hozzáadásához kattintson a \"Címkék\" felirat alatt taláható plusz gombra. 
      <br>
      A \"Címkék hozzáadása\" ablakban lehetősége van a létrehozott címkéit kiválasztani a mellettük található jelölőnégyzet segítségével és a mellettük található gombbal
      törölheti azokat. 
      Ebből az ablakból érhető el a \"Címke adatai\" ablak is, melyben új címkéket hozhat létre. Amennyiben egy címke adatait módosítani szeretné kattintson a 
      \"Címkék hozzáadása\" ablak egy címkéjére. <br>
      A \"Címke adatai\" ablakban lehetősége nyílik beállítani, illetve módosítani egy címke nevét, színét, illetve ikont is rendelhet hozzájuk.
    </p>
  </div>

  <br>

  <div class='row'>
    <h4>Elfelejtett jelszó</h4>
    <p>
      Amennyiben elfelejtette jelszavát, a <a href='index.php?page=bejelentkezes'>Bejelentkezés</a> menüpont alatt található \"Elfelejtett jelszó\" 
      hivatkozás segítségével új jelszót generálunk Önnek.
      Az új jelszót e-mailben elküldjük, majd az ezzel való bejelentkezés után a <a href='index.php?page=jelszo_valtoztatas'>Jelszó változtatás</a> 
      menüpontban új jelszót adhat meg magának.
    </p>
  </div>

  <br>

  <div class='row'>
    <h4>Jelszó változtatás</h4>
    <p>
      Amennyiben szeretné jelszavát megváltoztatni, bejelentkezés után a <a href='index.php?page=jelszo_valtoztatas'>Jelszó változtatás</a> menüpontból ezt megteheti.
      Kérem, hogy a jelszót melyet e-mailben küldünk Önnek
      változtassa meg itt mielőbb fiókja védelme érdekében.
    </p>
  </div>
</div>




";

?>

<script>
  document.title = "Student Planner - Kezdőlap";
</script>