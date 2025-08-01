<?php
session_start();
include '../includes/db.php';
include '../includes/auth_user.php';

$user_id = $_SESSION['user_id'];

// Handle delete item
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $delete_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}

// Handle cart quantity update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    foreach ($_POST['quantities'] as $product_id => $qty) {
        $qty = max(1, (int) $qty);
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $qty, $user_id, $product_id);
        $stmt->execute();
    }
    header("Location: cart.php");
    exit();
}

// Get user's cart
$sql = "SELECT c.*, p.name, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$cart_items = $res->fetch_all(MYSQLI_ASSOC);

$total = 0;
?>

<?php include '../includes/user_header.php'; ?>

<div class="container mt-5">
    <h3 class="mb-4">🛒 Your Shopping Cart</h3>

    <?php if (count($cart_items) === 0): ?>
        <p class="alert alert-warning">Your cart is empty.</p>
        <a href="../user/dashboard.php" class="btn btn-primary">← Continue Shopping</a>
    <?php else: ?>
        <form method="POST">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td><img src="/computer-accessories/uploads/<?= htmlspecialchars($item['image']) ?>" width="70"
                                    class="img-thumbnail"></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td style="width: 100px;">
                                <input type="number" name="quantities[<?= $item['product_id'] ?>]"
                                    value="<?= $item['quantity'] ?>" class="form-control text-center" min="1">
                            </td>
                            <td>₹<?= number_format($subtotal, 2) ?></td>
                            <td>
                                <a href="cart.php?delete=<?= $item['product_id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Remove this item from your cart?');">🗑️ Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" name="update" class="btn btn-primary">🔁 Update Cart</button>
                <h4>Total: ₹<?= number_format($total, 2) ?></h4>
                <a href="checkout.php" class="btn btn-success">✅ Checkout</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/user_footer.php'; ?>