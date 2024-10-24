<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /smart-pill-box/views/pages/login.php");
    exit();
}
