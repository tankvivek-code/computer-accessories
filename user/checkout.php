<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? 'COD';

    // Get cart items
    $stmt = $conn->prepare("SELECT c.*, p.name, p.price, p.stock 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart = $stmt->get_result();

    if ($cart->num_rows === 0) {
        echo "<script>alert('🛒 Your cart is empty!'); window.location.href='home.php';</script>";
        exit();
    }

    // Create order
    $insert_order = $conn->prepare("INSERT INTO orders (user_id, payment_method) VALUES (?, ?)");
    $insert_order->bind_param("is", $user_id, $payment_method);
    $insert_order->execute();
    $order_id = $conn->insert_id;

    // Insert order items
    while ($item = $cart->fetch_assoc()) {
        $product_id = $item['product_id'];
        $quantity = (int) $item['quantity'];
        $price = (float) $item['price'];
        $stock = (int) $item['stock'];
        $name = htmlspecialchars($item['name']);

        if ($quantity > $stock) {
            echo "<script>alert('❌ Not enough stock for {$name}'); window.location.href='cart.php';</script>";
            exit();
        }

        // Insert into order_items
        $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $insert_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        $insert_item->execute();

        // Reduce stock
        $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update_stock->bind_param("ii", $quantity, $product_id);
        $update_stock->execute();
    }

    // Clear cart
    $clear_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear_cart->bind_param("i", $user_id);
    $clear_cart->execute();

    echo "<script>alert('🎉 Order placed successfully!'); window.location.href='profile.php';</script>";
    exit();
}
?>

<!-- UI Part -->
<?php include '../includes/user_header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">🧾 Checkout</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="payment_method" class="form-label">💳 Choose Payment Method:</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="COD">Cash on Delivery</option>
                <option value="UPI">UPI</option>
                <option value="Card">Debit/Credit Card</option>
                <option value="NetBanking">Net Banking</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">✅ Place Order</button>
        <a href="cart.php" class="btn btn-secondary">🔙 Back to Cart</a>
    </form>
</div>

<?php include '../includes/user_footer.php'; ?>