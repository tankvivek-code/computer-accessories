<?php
session_start();
include '../includes/db.php';
include '../includes/auth_admin.php';

$id = $_GET['id'] ?? 0;

// Get product to delete
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "❌ Product not found.";
    exit;
}

$product = $result->fetch_assoc();


$imagePath = "../uploads/" . $product['image'];
if (file_exists($imagePath)) {
    unlink($imagePath); // delete image from server
}


$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: products.php");
exit;
?>