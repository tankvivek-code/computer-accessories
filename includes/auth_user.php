<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /computer-accessories/login.php');
    exit;
}
// Example roles: admin, user
