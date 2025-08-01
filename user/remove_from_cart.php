<?php
session_start();
include_once __DIR__ . '/../includes/auth_user.php';
include_once __DIR__ . '/../includes/db.php';

$uid = $_SESSION['user_id'];
$cid = intval($_GET['id']);
$conn->query("DELETE FROM cart WHERE id=$cid AND user_id=$uid");

header("Location: cart.php");
exit;
