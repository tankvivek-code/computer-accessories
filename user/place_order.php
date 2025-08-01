<?php
session_start();
include_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.product_id, c.quantity, p.price 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: cart.php");
    exit();
}

// Create new order
$conn->query("INSERT INTO orders (user_id, status) VALUES ($user_id, 'pending')");
$order_id = $conn->insert_id;

// Insert order items
while ($item = $result->fetch_assoc()) {
    $pid = $item['product_id'];
    $qty = $item['quantity'];
    $price = $item['price'];

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $pid, $qty, $price);
    $stmt->execute();
}

// Clear cart
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Redirect to order history
header("Location: checkout.php?success=1");
exit();
