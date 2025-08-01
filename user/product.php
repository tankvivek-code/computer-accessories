<?php
session_start();
include_once '../includes/auth_user.php';
include_once '../includes/db.php';
include_once '../includes/user_header.php';

if (!isset($_GET['id'])) {
    die("Product not found");
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM products WHERE id=$id");

if (!$res || $res->num_rows === 0) {
    die("Product not found");
}

$product = $res->fetch_assoc();
$in_stock = (int) $product['stock'];
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" class="img-fluid"
                alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <h4>₹<?= number_format($product['price'], 2) ?></h4>
            <p><strong>Stock:</strong>
                <?= $in_stock > 0 ? $in_stock : "<span class='text-danger'>Out of Stock</span>" ?></p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <?php if ($in_stock > 0): ?>
                <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-success">Add to Cart</a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>Out of Stock</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once '../includes/user_footer.php'; ?>