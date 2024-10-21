<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /pi3-smart-pill-box/views/pages/login.php");
    exit();
}
