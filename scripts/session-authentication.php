<?php

session_start();

if (!isset($_SESSION['logged_nursing_home'])) {
    print_r($_SESSION);
    // header("Location: /smart-pill-box/views/pages/login.php");
    // exit();
}
