<?php
session_start();
include_once __DIR__ . '/../includes/auth_user.php';
include_once __DIR__ . '/../includes/db.php';
include_once __DIR__ . '/../includes/user_header.php';

$uid = $_SESSION['user_id'];
$res = $conn->query("
    SELECT o.id, o.created_at, SUM(oi.quantity * p.price) AS total
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = $uid
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
?>
<div class="container my-5">
    <h3 class="mb-4 text-center text-md-start">📦 Your Orders</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ Order placed successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle text-center text-md-start">
            <thead class="table-dark">
                <tr>
                    <th scope="col">🆔 Order ID</th>
                    <th scope="col">📅 Date</th>
                    <th scope="col">💰 Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($res->num_rows > 0): ?>
                    <?php while ($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($row['id']) ?></td>
                            <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                            <td class="fw-bold text-success">₹<?= number_format($row['total'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-muted py-4">🙁 No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once __DIR__ . '/../includes/user_footer.php'; ?>