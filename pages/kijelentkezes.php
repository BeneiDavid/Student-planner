<?php

    session_unset();
    session_destroy();

    // automatikus átirányítás
    print '<meta http-equiv="refresh" content="0;url=index.php">';

?>