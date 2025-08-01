<?php
session_start();
include '../includes/db.php';
include '../includes/auth_user.php';
include '../includes/user_header.php';

$uid = $_SESSION['user_id'] ?? 0;

$cart_res = $conn->query("
    SELECT c.*, p.name, p.price, p.stock 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $uid
");

if ($cart_res->num_rows === 0) {
    echo "<div class='container mt-5'><h4>Your cart is empty!</h4></div>";
    include '../includes/user_footer.php';
    exit;
}

$total = 0;
$items = [];
$errors = [];

while ($row = $cart_res->fetch_assoc()) {
    if ($row['quantity'] > $row['stock']) {
        $errors[] = "❌ Product '{$row['name']}' only has {$row['stock']} in stock.";
    }
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $items[] = $row;
}

if (!empty($errors)) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>";
    foreach ($errors as $e)
        echo "<p>$e</p>";
    echo "</div><a href='dashboard.php' class='btn btn-warning'>🔁 Back to Cart</a></div>";
    include '../includes/user_footer.php';
    exit;
}

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id, status, total_amount, created_at) VALUES (?, 'Pending', ?, NOW())");
$stmt->bind_param("id", $uid, $total);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert order items & update stock
foreach ($items as $item) {
    $pid = $item['product_id'];
    $qty = $item['quantity'];
    $price = $item['price'];

    // Insert into order_items
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $pid, $qty, $price)");

    // Subtract from stock
    $conn->query("UPDATE products SET stock = stock - $qty WHERE id = $pid");
}

// Clear cart
$conn->query("DELETE FROM cart WHERE user_id = $uid");
?>

<div class="container mt-5 text-center">
    <h2>✅ Thank you for your order!</h2>
    <p>Your order ID is <strong>#<?= $order_id ?></strong></p>
    <p>Total Amount: ₹<?= number_format($total, 2) ?></p>
    <a href="../user/dashboard.php" class="btn btn-primary mt-3">Continue Shopping</a>
</div>

<?php include '../includes/user_footer.php'; ?>