<?php
session_start();
include '../includes/db.php';
include '../includes/auth_user.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$product_id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: dashboard.php");
    exit();
}
?>

<?php include '../includes/user_header.php'; ?>

<div class="container mt-5">
    <div class="row g-4">
        <div class="col-12 col-md-6">
            <img src="/computer-accessories/uploads/<?= htmlspecialchars($product['image']) ?>"
                class="img-fluid rounded shadow" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="col-12 col-md-6">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <h4 class="text-success">₹<?= number_format($product['price'], 2) ?></h4>
            <p class="text-muted">Stock: <?= $product['stock'] ?></p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <div class="d-flex flex-column flex-md-row gap-2 mt-3">
                <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-primary">🛒 Add to Cart</a>
                <a href="dashboard.php" class="btn btn-outline-secondary">← Back</a>
            </div>
        </div>
    </div>
    <?php include '../includes/user_footer.php'; ?>
</div>