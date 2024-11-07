<?php

session_start();

if (!isset($_SESSION['logged_nursing_home_id'])) {
    header("Location: /smart-pill-box/views/pages/login.php");
    exit();
}
