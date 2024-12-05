<?php
include("../config/connect.php");
// Xóa session PHP
session_start();
unset($_SESSION['user']);
session_destroy();

header("Location: https://accounts.google.com/Logout");

header('location: ../index.php');
exit;
