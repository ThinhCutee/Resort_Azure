<?php
include("../config/connect.php");
session_start();
unset($_SESSION['user']);
header('location: http://localhost/Resort_Azure/dichvu.php?id_dich_vu=' . $_SESSION['id_dich_vu']);
session_destroy();
exit;
