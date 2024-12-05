<?php
session_start();

include('inc/database.php');

// Xử lý đăng nhập khi nhấn nút LOGIN
if(isset($_POST['login'])) {
    $admin_name = $_POST['admin_name'];
    $admin_pass = $_POST['admin_pass'];
    login($admin_name, $admin_pass);
}