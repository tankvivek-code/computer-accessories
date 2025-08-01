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

<div class="container mt-4">
    <h3>📦 Your Orders</h3>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Order placed successfully!</div>
    <?php endif; ?>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $row['id'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>₹<?= number_format($row['total'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once __DIR__ . '/../includes/user_footer.php'; ?>