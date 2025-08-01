<?php
include_once __DIR__ . '/../includes/auth_user.php';
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/user_header.php';

$res = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<div class="container mt-4">
    <h3>🛒 Welcome, <?= $_SESSION['user_name'] ?? 'User' ?></h3>
    <p>Browse our latest accessories!</p>

    <div class="row">
        <?php while ($row = $res->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-center" style="height: 180px; overflow: hidden;">
                        <img src="/computer-accessories/uploads/<?= htmlspecialchars($row['image']) ?>" class="img-fluid"
                            style="max-height: 160px; object-fit: contain;" alt="<?= htmlspecialchars($row['name']) ?>">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                        <p class="card-text fw-semibold text-success">₹<?= number_format($row['price'], 2) ?></p>
                        <p class="text-muted mb-2">Stock: <?= $row['stock'] ?></p>
                        <div class="mt-auto">
                            <a href="product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-dark me-1">View</a>
                            <?php if ($row['stock'] > 0): ?>
                                <a href="add_to_cart.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Add to Cart</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/user_footer.php'; ?>