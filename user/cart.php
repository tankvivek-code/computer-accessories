<?php
session_start();
include '../includes/db.php';
include '../includes/auth_user.php';

$user_id = $_SESSION['user_id'];

// Delete item
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $delete_id);
    $stmt->execute();
    header("Location: cart.php");
    exit();
}

// AJAX update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax_update'])) {
    $product_id = (int) $_POST['product_id'];
    $qty = max(1, (int) $_POST['quantity']);

    $stock_stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stock_stmt->bind_param("i", $product_id);
    $stock_stmt->execute();
    $stock = $stock_stmt->get_result()->fetch_assoc()['stock'] ?? 1;

    if ($qty > $stock)
        $qty = $stock;

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $qty, $user_id, $product_id);
    $stmt->execute();

    $sql = "SELECT c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ? AND c.product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $subtotal = $res['price'] * $res['quantity'];

    $sql_total = "SELECT SUM(c.quantity * p.price) AS total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql_total);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res_total = $stmt->get_result()->fetch_assoc()['total'];

    echo json_encode([
        "quantity" => $res['quantity'],
        "subtotal" => number_format($subtotal, 2),
        "total" => number_format($res_total, 2)
    ]);
    exit();
}

// Get cart
$sql = "SELECT c.*, p.name, p.price, p.image, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$total = 0;
?>

<?php include '../includes/user_header.php'; ?>

<div class="container py-4">
    <h3 class="mb-4 text-center fw-bold">🛒 Your Shopping Cart</h3>

    <?php if (count($cart_items) === 0): ?>
        <p class="alert alert-warning text-center">Your cart is empty.</p>
        <div class="text-center">
            <a href="../user/dashboard.php" class="btn btn-primary btn-lg">← Continue Shopping</a>
        </div>
    <?php else: ?>
        <!-- Desktop Table -->
        <div class="d-none d-md-block table-responsive shadow-sm rounded-3">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center text-sm-start">
                    <?php foreach ($cart_items as $item):
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        ?>
                        <tr id="row-<?= $item['product_id'] ?>">
                            <td><img src="/computer-accessories/uploads/<?= htmlspecialchars($item['image']) ?>" width="70"
                                    class="img-thumbnail rounded"></td>
                            <td><?= htmlspecialchars($item['name']) ?><br><small class="text-muted">Stock:
                                    <?= $item['stock'] ?></small></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <div class="input-group" style="max-width:150px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="changeQty(<?= $item['product_id'] ?>,-1,<?= $item['stock'] ?>)">−</button>
                                    <input type="number" id="qty-<?= $item['product_id'] ?>" value="<?= $item['quantity'] ?>"
                                        class="form-control text-center" min="1" max="<?= $item['stock'] ?>"
                                        onchange="updateCart(<?= $item['product_id'] ?>)">
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        onclick="changeQty(<?= $item['product_id'] ?>,1,<?= $item['stock'] ?>)">+</button>
                                </div>
                            </td>
                            <td id="subtotal-<?= $item['product_id'] ?>">₹<?= number_format($subtotal, 2) ?></td>
                            <td><a href="cart.php?delete=<?= $item['product_id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Remove this item?');">🗑️ Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="row d-md-none">
            <?php foreach ($cart_items as $item):
                $subtotal = $item['price'] * $item['quantity'];
                ?>
                <div class="col-12 col-sm-6 mb-3">
                    <div class="card shadow-sm rounded-3 h-100">
                        <div class="p-2">
                            <img src="/computer-accessories/uploads/<?= htmlspecialchars($item['image']) ?>"
                                class="img-fluid rounded mb-2">
                            <h6><?= htmlspecialchars($item['name']) ?></h6>
                            <p class="mb-1">Price: ₹<?= number_format($item['price'], 2) ?></p>
                            <p class="mb-1 text-muted">Stock: <?= $item['stock'] ?></p>
                            <div class="input-group mb-2" style="max-width:150px;">
                                <button class="btn btn-outline-secondary btn-sm"
                                    onclick="changeQty(<?= $item['product_id'] ?>,-1,<?= $item['stock'] ?>)">−</button>
                                <input type="number" id="qty-<?= $item['product_id'] ?>" value="<?= $item['quantity'] ?>"
                                    class="form-control text-center" min="1" max="<?= $item['stock'] ?>"
                                    onchange="updateCart(<?= $item['product_id'] ?>)">
                                <button class="btn btn-outline-secondary btn-sm"
                                    onclick="changeQty(<?= $item['product_id'] ?>,1,<?= $item['stock'] ?>)">+</button>
                            </div>
                            <p class="mb-1">Subtotal: ₹<span
                                    id="subtotal-<?= $item['product_id'] ?>"><?= number_format($subtotal, 2) ?></span></p>
                            <a href="cart.php?delete=<?= $item['product_id'] ?>" class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('Remove this item?');">🗑️ Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total + Checkout -->
        <div class="card p-3 mt-3 shadow-sm rounded-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0">Total: ₹<span id="cart-total"><?= number_format($total, 2) ?></span></h4>
                <a href="checkout.php" class="btn btn-success btn-lg">✅ Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function changeQty(productId, change, maxStock = 99) {
        let input = document.getElementById("qty-" + productId);
        let val = parseInt(input.value) + change;
        if (val < 1) val = 1;
        if (val > maxStock) val = maxStock;
        input.value = val;
        updateCart(productId);
    }

    function updateCart(productId) {
        let input = document.getElementById("qty-" + productId);
        let qty = parseInt(input.value);
        fetch("cart.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "ajax_update=1&product_id=" + productId + "&quantity=" + qty
        }).then(res => res.json()).then(data => {
            document.getElementById("qty-" + productId).value = data.quantity;
            document.getElementById("subtotal-" + productId).innerText = data.subtotal;
            document.getElementById("cart-total").innerText = data.total;
        });
    }
</script>

<?php include '../includes/user_footer.php'; ?>