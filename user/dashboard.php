<?php
include_once __DIR__ . '/../includes/auth_user.php';
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/user_header.php';

$res = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<div class="container py-5">
    <h2 class="mb-3 fw-bold text-center text-primary">🖥️ Welcome,
        <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h2>
    <p class="text-center text-muted mb-4">Explore our latest computer accessories collection</p>

    <div class="row g-4">
        <?php while ($row = $res->fetch_assoc()): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow border-0 rounded-3 product-card transition">
                    <div class="bg-light d-flex justify-content-center align-items-center"
                        style="height: 200px; overflow: hidden;">
                        <img src="/computer-accessories/uploads/<?= htmlspecialchars($row['image']) ?>"
                            class="img-fluid p-3" style="max-height: 180px; object-fit: contain;"
                            alt="<?= htmlspecialchars($row['name']) ?>">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-dark"><?= htmlspecialchars($row['name']) ?></h5>
                        <p class="text-success fw-bold mb-1">₹<?= number_format($row['price'], 2) ?></p>
                        <p class="text-muted small mb-2">Stock: <?= (int) $row['stock'] ?></p>
                        <div class="mt-auto d-flex justify-content-between gap-2">
                            <a href="product.php?id=<?= urlencode($row['id']) ?>"
                                class="btn btn-outline-primary btn-sm w-50">🔍 View</a>
                            <?php if ((int) $row['stock'] > 0): ?>
                                <a href="add_to_cart.php?id=<?= urlencode($row['id']) ?>" class="btn btn-success btn-sm w-50">🛒
                                    Add</a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm w-50" disabled>Out of Stock</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/user_footer.php'; ?>