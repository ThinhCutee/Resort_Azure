<?php
    function adminLogin(){
        session_start();
        if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)) {
            echo "<script>window.location.href='./dashboard/index.php';</script>";
        }
    }
    function direct($url)
    {
        echo "<script>window.location.href='$url';</script>";
    }
    function alert($type, $msg)
    {
        $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
        echo '<div class="alert ' . $bs_class . ' alert-dismissible fade show custom-alert text-center" role="alert">
                <strong class="me-3">' . $msg . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
?>
