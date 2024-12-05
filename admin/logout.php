<?php
    include('inc/essentials.php');
    // $_SESSION['adminLogin'] = false;
    session_start();
    session_destroy();
    direct('index.php');
?>