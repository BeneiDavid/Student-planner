<?php

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 'yes')
{
    echo "<h1>Üzenetek</h1>";
}
else{
    print '<p class="bg-warning text-white"><a href="index.php?page=bejelentkezes"> Ön még nem jelentkezett be, kérem jelentkezzen be itt!</a></p>';
}

?>