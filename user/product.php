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
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="row g-4 align-items-start shadow rounded p-4 bg-white">
                <div class="col-md-6">
                    <div class="border rounded overflow-hidden">
                        <img src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid w-100 object-fit-cover"
                            style="max-height: 400px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="mb-3"><?= htmlspecialchars($product['name']) ?></h2>
                    <h4 class="text-success mb-3">₹<?= number_format($product['price'], 2) ?></h4>

                    <p class="mb-2"><strong>Stock:</strong>
                        <?= $in_stock > 0
                            ? "<span class='text-success fw-semibold'>$in_stock Available</span>"
                            : "<span class='text-danger fw-semibold'>Out of Stock</span>" ?>
                    </p>

                    <p class="mb-4"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

                    <?php if ($in_stock > 0): ?>
                        <a href="add_to_cart.php?id=<?= $product['id'] ?>" class="btn btn-success px-4 py-2">
                            🛒 Add to Cart
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary px-4 py-2" disabled>
                            Out of Stock
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once '../includes/user_footer.php'; ?>